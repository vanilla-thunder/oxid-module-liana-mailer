<?php

/**
 *  ___NAME___
 *  Copyright (C) ___YEAR___  ___COMPANY___
 *  info:  ___EMAIL___
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 **/

$sMetadataVersion = '1.1';
$aModule = [
	'id'          => 'liana-mailer',
	'title'       => '<strong style="color:#95b900;font-size:125%;">best</strong><strong style="color:#c4ca77;font-size:125%;">life</strong> <strong>liana-mailer integration</strong>',
	'description' => '___DESCRIPTION___',
	'thumbnail'   => '../bestlife.png',
	'version'     => '___VERSION___',
	'author'      => '___AUTHOR___, ___COMPANY___',
	'email'       => '___EMAIL___',
	'url'         => '___URL___',
	'extend'      => [

		'newsletter' => 'bla/liana-mailer/application/extend/newsletter_liana'
	],
	'templates'   => [],
	'blocks'      => [
		[
			'template' => 'form/newsletter.tpl',
			'block'    => 'newsletter_form_status',
			'file'     => '/application/views/blocks/newsletter_form_status.tpl'
		],[
			'template' => 'form/newsletter.tpl',
			'block'    => 'newsletter_form_submit',
			'file'     => '/application/views/blocks/newsletter_form_submit.tpl'
		]
	],
	'settings'    => [
		[
			'group' => 'blaLianaSettings',
			'name'  => 'sBlaLianaPageURL',
			'type'  => 'str',
			'value' => ''
		],
		[
			'group' => 'blaLianaSettings',
			'name'  => 'sBlaLianaListID',
			'type'  => 'str',
			'value' => ''
		],
		[
			'group' => 'blaLianaSettings',
			'name'  => 'aBlaLianaConsents',
			'type'  => 'arr',
			'value' => []
		]
	]
];
