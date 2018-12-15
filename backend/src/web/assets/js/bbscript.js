
var Model = Backbone.Model.extend({
    checkValidation: function () {
        var rules = this.rules;
        var attrs = this.attributes;
        var validation = [];

        for(var key in rules) {
            var rulesValueParts = (rules[key]).split('|');
            if(rulesValueParts.length > 1) {
                for( var i=0; i < rulesValueParts.length; i++) {
                    console.log('RuleValue: ' + rulesValueParts[i]);
                    var valid = this.applyRule(attrs[key], rulesValueParts[i]);
                    if(valid !== true) {
                        validation[key] = valid;
                    }
                }
            } else {
                var valid = this.applyRule(attrs[key], rules[key]);
                if(valid !== true) {
                    validation[key] = valid;
                }
            }
        }
        console.log(validation);
    },

    applyRule: function (attrValue, ruleName) {

        console.log('Inside applyRule: ', attrValue, ruleName);

        switch(ruleName) {
            case 'integer':
                var ret = Number.isInteger(parseInt(attrValue)) ? true : attrValue + ' is not an integer' ;
                console.log('Applying rule integer on ', attrValue, ret);
                return ret;
                break;
            case 'required':
                var ret = attrValue ? true : attrValue + ' is not defined';
                console.log('Applying rule required on ', attrValue);
                return ret;
                break;
            case 'string':
                var ret = typeof attrValue == 'string' ? true : attrValue + ' is string';
                console.log('Applying rule string on ', attrValue, ret);
                break;
        }
    },
    commonMethod: function () {
        console.log('This is a common method from the parent.');
    }

});

var Post = Model.extend({
    urlRoot: '/api/v1/posts', // very important for delete method
    idAttribute: 'id', // very important for delete method else delete will fire only on api/posts without id
    defaults: {
        "id": "1",
        "title": "Initial title",
        "description": "Initial description",
    },
    initialize: function () {
        console.log('A new post has been created.');
    },
    commonMethod: function () {
        Model.prototype.commonMethod();
        console.log('This is a common method from the child.');
    },
    rules: {
        id: 'integer',
        title: 'string|required|minLen=10',
        description: 'string'
    },
    validate: function () {
        this.checkValidation();
    }
});

// Collections
var Posts = Backbone.Collection.extend({
    model : Post,
    url: '/api/v1/posts'
});

var posts = new Posts();

// Fetching collections
posts.fetch({
    success: function (resp) {
        console.log('Fetching posts success.');
        // webPosts.index(posts);
    },
    error: function () {
        console.log('Error in fetching posts.');
    }
});

// View for one model
var PostView = Backbone.View.extend({
    model: new Post(),
    tagName: 'tr',
    events: {
        'click .item-edit' : 'edit',
        'click .item-delete' : 'delete',
        'click .item-update' : 'update',
        'click .item-cancel' : 'cancel'
    },
    controls: function() {
        return {
            title: this.$('.title'),
            author: this.$('.author'),
            artist: this.$('.artist')
        }
    },
    controlsData: {},
    setControlsData: function (data) {
        this.controlsData = {
            title: data.title,
            author: data.author,
            artist: data.artist
        };
        return this.controlsData;
    },
    edit: function () {
        this.setControlsData({
                title: this.$('.title').html(),
                author: this.$('.author').html(),
                artist: this.$('.artist').html()
        });
        this.controls().title.html('<input type="text" class="form-control title-update" value="'+this.controlsData.title+'" />');
        this.controls().author.html('<input type="text" class="form-control author-update" value="'+this.controlsData.author+'" />');
        this.controls().artist.html('<input type="text" class="form-control artist-update" value="'+this.controlsData.artist+'" />');
        this.toggleButtons();
    },
    update: function () {
        var data = {
            title: this.$('.title-update').val(),
            author: this.$('.author-update').val(),
            artist: this.$('.artist-update').val()
        };
        this.setControlsData(data);

        window.cd = this.controlsData;
        console.log(this.controlsData);

        this.controls().title.html(this.controlsData.title);
        this.controls().author.html(this.controlsData.author);
        this.controls().artist.html(this.controlsData.artist);
        console.log(this.model.save(data));

        this.toggleButtons();
    },
    cancel: function () {
        this.controls().title.html(this.controlsData.title);
        this.controls().author.html(this.controlsData.author);
        this.controls().artist.html(this.controlsData.artist);
        this.toggleButtons();
    },
    delete: function () {
        // console.log('Removing model: ' + this.model.get('id'));
        posts.remove(this.model);
        this.model.destroy();
        this.$el.remove();
    },
    toggleButtons: function () {
        this.$('.item-edit').toggle();
        this.$('.item-delete').toggle();

        this.$('.item-update').toggle();
        this.$('.item-cancel').toggle();
    },
    initialize: function () {
        this.template = _.template($('.post-item-template').html());
    },
    render: function () {
        this.$el.html(this.template(this.model.toJSON()));
        return this;
    }
});


// View for all models
var PostsView = Backbone.View.extend({
    model : posts,
    el: $('.post-index'), // tbody
    initialize: function () {
        console.log('Inside posts view' + posts.toArray());
        this.model.on('add', this.render, this);
    },
    render: function () {
        var self = this;
        this.$el.html('');
        _.each(this.model.toArray(), function (sng) {
            self.$el.append((new PostView({model:sng})).render().$el)
        });
        return this;
    }
});

