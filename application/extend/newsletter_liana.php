<?php

/**
 * [___VENDOR___] ___NAME___
 * Copyright (C) ___YEAR___  ___COMPANY___
 * info:  ___EMAIL___
 *
 * This program is free software;
 * you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation;
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses/>
 *
 * author: ___AUTHOR___
 */

class newsletter_liana extends newsletter_liana_parent
{
	private function _getUserIdByUserName($sUserName, $ShopId = null)
	{
		if(!$ShopId) $ShopId = $this->getConfig()->getShopId();

		$sSelect = "SELECT `OXID` FROM `oxuser` WHERE `OXACTIVE` = 1 AND `OXUSERNAME` = ? AND `OXPASSWORD` != ''";

		if ($this->getConfig()->getConfigParam('blMallUsers')) $sSelect .= "ORDER BY OXSHOPID = ? DESC";
		else $sSelect .= "AND OXSHOPID = ?";

		$sOxId = oxDb::getDb()->getOne( $sSelect, [ $sUserName, $ShopId ]);

		return $sOxId;
	}

	public function send ()
	{
		// GDPR checkbox
		$gdprcheckbox = oxRegistry::getConfig()->getRequestParameter("gdprcheckbox");
		if (!$gdprcheckbox)
		{
			oxRegistry::get("oxUtilsView")->addErrorToDisplay('LIANA_NEWSLETTER_PLEASE_ACCEPT_GDPR');

			return;
		}

		// ab hier original code

		$aParams = oxRegistry::getConfig()->getRequestParameter("editval");

		// loads submited values
		$this->_aRegParams = $aParams;

		if (!$aParams['oxuser__oxusername'])
		{
			oxRegistry::get("oxUtilsView")->addErrorToDisplay('ERROR_MESSAGE_COMPLETE_FIELDS_CORRECTLY');

			return;
		}
		else
		{
			if (!oxRegistry::getUtils()->isValidEmail($aParams['oxuser__oxusername']))
			{
				// #1052C - eMail validation added
				oxRegistry::get("oxUtilsView")->addErrorToDisplay('MESSAGE_INVALID_EMAIL');

				return;
			}
		}

		// bisher original, ab jetzt verändert

		// liana mailer configuratiom
		$lmCfg = (object)[
			"url"      => oxRegistry::getConfig()->getConfigParam("sBlaLianaPageURL"),
			"listid"   => intval(oxRegistry::getConfig()->getConfigParam("sBlaLianaListID")),
			"consents" => oxRegistry::getConfig()->getConfigParam("aBlaLianaConsents")

		];

		if (!$lmCfg->url || !$lmCfg->listid)
		{
			oxRegistry::get("oxUtilsView")->addErrorToDisplay('LIANA_NEWSLETTER_NOT_CONFIGURED');
			return;
		}


		$_data = [
			"email" => $aParams['oxuser__oxusername'],
			"anrede"   => oxRegistry::getLang()->translateString($aParams['oxuser__oxsal']),
			"vorname"  => $aParams['oxuser__oxfname'],
			"nachname" => $aParams['oxuser__oxlname'],
			"join"     => $lmCfg->listid,
			"consent"  => $lmCfg->consents
		];

		// subscribe or unsubscribe?
		/*
			if ($blSubscribe)


			else $_data = array_merge($_data, [
				"leave" => "1",
				"cancel_reason_type" => "",
				"cancel_reason_text" => "",
				"lm-gtfo" => "", // ?????
				"action" => "set"
			]);
		*/

		//echo "<pre>";

		// Daten aus dem Benutzerprofil holen?
		$sOxId = $this->_getUserIdByUserName($aParams['oxuser__oxusername']);
		//var_dump($aParams['oxuser__oxusername']);
		//var_dump($sOxId);

		$oUser = oxNew('oxuser');
		if($sOxId && ( !$_data['anrede'] || !$_data['vorname'] || !$_data['nachnamename'] ) && $oUser->load($sOxId) )
		{
			if(!$_data['anrede']) $_data['anrede'] = oxRegistry::getLang()->translateString($oUser->oxuser__oxsal->value);
			if(!$_data['vorname']) $_data['vorname'] = $oUser->oxuser__oxfname->value;
			if(!$_data['nachname']) $_data['nachname'] = $oUser->oxuser__oxlname->value;
		}

		$ch = curl_init($lmCfg->url);
		curl_setopt($ch, CURLOPT_POST, 1);

		/*
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($_data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'Content-Type: application/json',
				'Content-Length: ' . strlen(json_encode($_data))
		]);
		*/

		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($_data, '', '&'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);

		$json = (object)json_decode($response);
/*
		echo "<pre>";
		var_dump($_data);
		var_dump($response);
		var_dump($json);
		*/

		if ( isset($json->success) && $json->success == true ) $this->_iNewsletterStatus = 1;
		else if ($json->error_msg) oxRegistry::get("oxUtilsView")->addErrorToDisplay($response->error_msg);
		else oxRegistry::get("oxUtilsView")->addErrorToDisplay($response->error_msg);
	}
}