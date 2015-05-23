<?php namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;
use AbuseIO\Http\Controllers\Controller;

use Illuminate\Http\Request;

class HomeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        return view('home');
	}

}
