<?php
namespace App;
/**
 * Set a flash message in the session.
 *
 * @param  string $message
 * @return void
 */
class Helpers 
{
	public static function flash($message) {
    	session()->flash('message', $message);
	}
}