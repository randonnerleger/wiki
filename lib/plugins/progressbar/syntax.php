<?php
/* DokuWiki Progressbar plugin
 * Internal version 1.0.0
 * 
 * Copyright (C) 2009 Mischa The Evil
 * Copyright (C) 2006 Mike Smith
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

class syntax_plugin_progressbar extends DokuWiki_Syntax_Plugin
{
 
	/**
	 * return some info
	 */
	function getInfo()
	{
		return array
		(
			'author' => 'Mischa The Evil',
			'email'  => 'mischa_the_evil@hotmail.com',
			'date'   => '2009-03-09',
			'name'   => 'Progressbar',
			'desc'   => 'Makes progress bars on wiki pages.',
			'url'    => 'http://www.dokuwiki.org/plugin:progressbar',
		);
	}
 
	/**
	 * What kind of syntax are we?
	 */
	function getType()
	{
		return 'substition';
	}
 
	/**
	 * Where to sort in?
	 */ 
	function getSort()
	{
		return 999;
	}
 
	/**
	 * Connect pattern to lexer
	 */
	function connectTo($mode)
	{
		$this->Lexer->addSpecialPattern('<progress=(?:10|[1-9]?)0>', $mode, 'plugin_progressbar');
	}
 
	/**
	 * Handle the match
	 */
#	function handle($match, $state, $pos, &$handler)
	function handle($match, $state, $pos, Doku_Handler $handler)
	{
		substr($match, 10, -1);
		return array(substr($match, 10, -1));
	}
 
	/**
	 * Create output
	 */
#	function render($mode, &$renderer, $data)
	function render($format, Doku_Renderer $renderer, $data)
	{
		$renderer->doc .= '<img width="100" height="16" src="'. DOKU_URL .'lib/plugins/progressbar/images/' . $data[0] . '.gif" alt="' . $data[0] . '% completed" title="' . $data[0] . '% completed" />';
		return true;
	}
}
