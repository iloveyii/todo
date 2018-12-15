<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 2018-10-22
 * Time: 12:05
 */

namespace App\Models;


/**
 * Interface IRequest
 * @package App\Models
 */
interface IRequest
{
    /**
     * Depending on the HTTP method it returns the corresponding vars
     * @return array of vars
     */
    public function body();

    /**
     * These two functions are for testing purposes only
     * if one wants to test the class from command line he can mimic the post request by setting the post/get array
     * using these methods
     *
     * @param array $post
     * @return mixed
     */
    public function setPostVars( array $post);
    public function setGetVars( array  $get );
}
