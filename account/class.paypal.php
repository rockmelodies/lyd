<?php
/********************************************************
********** Created By: Hirak Chattopadhyay **************
*********************************************************
******************** Paypal Class ***********************
*********************************************************/

class paypal{
	
	
	/*public $API_USERNAME = 'hirak94-facilitator_api1.gmail.com';
	public $API_PASSWORD = '1368806211';
	public $API_SIGNATURE = 'A1Z7xIEESITxOSFPh5OSDWn2psvqANHLcMklwMqcHv3kNbMqEBxGmcqW';
	public $API_ENDPOINT = 'https://api-3t.sandbox.paypal.com/nvp';
	public $API_ENVIRONMENT = 'sandbox';
	*/
	
	public $API_USERNAME = 'eastprojinv_api1.lydormarkets.com';
	public $API_PASSWORD = '2URYWE4B2TZKDTLJ';
	public $API_SIGNATURE = 'AFcWxV21C7fd0v3bYYYRCpSSRl31AjsNT5YsorpGtCRCcCcGwoHWEQLY';
	public $API_ENDPOINT = 'https://api-3t.paypal.com/nvp';
	public $API_ENVIRONMENT = 'live';
	
	public $API_VERSION = '64.0';
	
	
	function DoDirectPayment($recurring =  false){
		
		$IpAddress = urlencode($this->IpAddress);
		$creditCardType =  urlencode($this->creditCardType);
		$creditCardNumber = urlencode($this->creditCardNumber);
		$expDate = urlencode($this->expDateMonth.$this->expDateYear);
		$cvv2Number = urlencode($this->cvv2Number);
		$firstName = urlencode($this->firstName);
		$lastName = urlencode($this->lastName);
		$email = urlencode($this->email);
		//$phoneNumber = urlencode($this->phone);
		$street = urlencode($this->street);
		$street2 = urlencode($this->street2);
		$city = urlencode($this->city);
		$state = urlencode($this->state);
		$zip = urlencode($this->zip);
		$countryCode = urlencode($this->countryCode);
		$description = urlencode($description);
		$paymentAmount = urlencode(number_format($this->paymentAmount,2));
		$nvpStr = "&PAYMENTACTION=Sale&IPADDRESS=$IpAddress&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=$expDate&CVV2=$cvv2Number&EMAIL=$email&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$street&STREET2=$street2&CITY=$city&STATE=$state&COUNTRYCODE=$countryCode&ZIP=$zip&AMT=$paymentAmount";
		
		$httpParsedResponseAr = $this->hash_call('DoDirectPayment', $nvpStr);
		
		//echo '<pre>'; print_r($httpParsedResponseAr); echo '</pre>' ;
		
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
			
			
			if($recurring == true){
				
				
				$profileStartDate = urlencode(date('Y-m-d H:i:s'));
				$Desc = $description;
				$billingPeriod = urlencode('Month');
				$billingFrequency = urlencode('1');
				$billingAmount = urlencode(number_format($_SESSION['mp'],2));
				
				$nvpStrRecurring = "&PROFILESTARTDATE=".$profileStartDate."&DESC=".$Desc."&BILLINGPERIOD=".$billingPeriod."&BILLINGFREQUENCY=".$billingFrequency."&AMT=".$billingAmount."&AUTOBILLOUTAMT=AddToNextBilling&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=$expDate&CVV2=$cvv2Number";
				
				$httpParsedResponseArRecurring = $this->hash_call('CreateRecurringPaymentsProfile', $nvpStrRecurring);
				
				//echo '<pre>'; print_r($httpParsedResponseArRecurring); echo '</pre>' ;
				
				if("SUCCESS" == strtoupper($httpParsedResponseArRecurring["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseArRecurring["ACK"])) {
					
					return array('SUCCESS' => 'RECURRING PAYMENT PROFILE CREATED.');
					
				} else  {
					
					return array('ERROR' => $httpParsedResponseArRecurring );
				
				}
				
			}else{
				return array('SUCCESS' => 'PAYMENT PROCESSED SUCCESSFULLY.');
			}
						
			//return array('TOKEN' => $httpParsedResponseAr);
			
		} else  {
			
			return array('ERROR' => $httpParsedResponseAr );
		
		}
		
	}
	
	
	
	function SetExpressCheckout(){
		
		$paymentAmount = urlencode(number_format($this->paymentAmount,2));
		$returnURL = $this->returnURL;
		$cancelURL = $this->cancelURL;
		$items = $this->items;
		$currency = $this->currency;
		
		$n = 0;
		$m = 0;
		$itemDetails='';
		foreach($items as $item){
			if(!empty($item['category']))
				$itemDetails .="&L_PAYMENTREQUEST_".$n."_ITEMCATEGORY".$m."=".urlencode($item['category']);
			else
				$itemDetails .="&L_PAYMENTREQUEST_".$n."_ITEMCATEGORY".$m."=Digital";
		
			if(!empty($item['name']))
				$itemDetails .="&L_PAYMENTREQUEST_".$n."_NAME".$m."=".urlencode($item['name']);
			
			if(!empty($item['qty']))
				$itemDetails .="&L_PAYMENTREQUEST_".$n."_QTY".$m."=".urlencode($item['qty']);
			
			if(!empty($item['amount']))
				$itemDetails .="&L_PAYMENTREQUEST_".$n."_AMT".$m."=".number_format($item['amount'],2);
				
			if(!empty($item['desc']))
				$itemDetails .="&L_PAYMENTREQUEST_".$n."_DESC".$m."=".urlencode($item['desc']);
				
			$n++;
		}
		
		$currencyCode = '';
		if(!empty($currency)){
			$currencyCode = "&PAYMENTREQUEST_0_CURRENCYCODE=".$currency; 
		}
		
		$nvpStrDesc = '';
		for( $i=0; $i < count($this->desc); $i++ ){
			$nvpStrDesc .= "&PAYMENTREQUEST_".$i."_DESC=".$this->desc[$i];
		}
		if(count($this->desc) < 1)
			$nvpStrDesc .= "&PAYMENTREQUEST_".$i."_DESC=SameEveryTime";
		
		$nvpStr = "&PAYMENTREQUEST_0_AMT=".$paymentAmount.$currencyCode."&RETURNURL=$returnURL&CANCELURL=$cancelURL&NOSHIPPING=1&PAYMENTREQUEST_0_PAYMENTACTION=Authorization".$nvpStrDesc.$itemDetails;
		
		$httpParsedResponseAr = $this->hash_call('SetExpressCheckout', $nvpStr);
		
		
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
			$token = urldecode($httpParsedResponseAr["TOKEN"]);
			$payPalURL = "https://www.paypal.com/webscr&cmd=_express-checkout&token=$token";
			if("sandbox" === $this->API_ENVIRONMENT || "beta-sandbox" === $this->API_ENVIRONMENT) {
				$payPalURL = "https://www.$this->API_ENVIRONMENT.paypal.com/webscr&cmd=_express-checkout&token=$token";
			}
			header("Location: $payPalURL");
			exit;
		} else  {
			
			return array('ERROR' => $httpParsedResponseAr );
		
		}
		
	}
	
	function GetExpressCheckoutDetails($token, $all = false){
		
		if(empty($token)) {
			
			return array('ERROR' => 'Token is not received.');
		
		}
		
		$nvpStr = "&TOKEN=".urlencode($token);
		
		$httpParsedResponseAr = $this->hash_call('GetExpressCheckoutDetails', $nvpStr);
		
		
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
			$payerID = $httpParsedResponseAr['PAYERID'];
			
			if(!$all)
				return array('PAYERID' => $payerID, 'TOKEN' => $token);
			else
				return $httpParsedResponseAr;
			
		} else  {
			
			return array('ERROR' => $httpParsedResponseAr);
		
		}
		
	}
	
	function DoExpressCheckoutPayment($payerId, $token, $amount, $currency){
	
		if(empty($token) || empty($payerId)) {
			
			return array('ERROR' =>'Token / PayerID is not received.');
			
		}
		
		$currencyCode = '';
		if(!empty($currency)){
			$currencyCode = "&PAYMENTREQUEST_0_CURRENCYCODE=".$currency; 
		}
		
		$paymentAmount = urlencode(number_format($amount,2));
		
		$nvpStr = "&TOKEN=".urlencode($token)."&PAYERID=".$payerId."&PAYMENTREQUEST_0_AMT=".$paymentAmount.$currencyCode."&PAYMENTREQUEST_0_PAYMENTACTION=Sale";
		
		$httpParsedResponseAr = $this->hash_call('DoExpressCheckoutPayment', $nvpStr);
		
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
			
			return array('TOKEN' => $token);
			
		} else  {
			
			return array('ERROR' => $httpParsedResponseAr );
			
		}
		
	}
	
	function CreateRecurringPaymentsProfile($token){
		
		if(empty($token)) {
			
			return array('ERROR' => 'Token is not received.');
		
		}
		
		$profileStartDate = urlencode($this->profileStartDate);
		$Desc = urlencode($this->desc);
		$billingPeriod = urlencode($this->billingPeriod);
		$billingFrequency = urlencode($this->billingFrequency);
		$billingAmount = urlencode(number_format($this->billingAmount,2));
		
		$nvpStr = "&TOKEN=".urlencode($token)."&PROFILESTARTDATE=".$profileStartDate."&DESC=".$Desc."&BILLINGPERIOD=".$billingPeriod."&BILLINGFREQUENCY=".$billingFrequency."&AMT=".$billingAmount."&AUTOBILLOUTAMT=AddToNextBilling";
		
		$httpParsedResponseAr = $this->hash_call('CreateRecurringPaymentsProfile', $nvpStr);
		
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
			
			return array('SUCCESS' => 'RECURRING PAYMENT PROFILE CREATED.');
			
		} else  {
			
			return array('ERROR' => $httpParsedResponseAr );
		
		}
		
	}
	
	function hash_call($methodName,$nvpStr)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->API_ENDPOINT);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		if($this->USE_PROXY)
		{
			curl_setopt ($ch, CURLOPT_PROXY, $this->PROXY_HOST.":".$this->PROXY_PORT); 
		}
		$nvpreq="METHOD=".urlencode($methodName)."&VERSION=".urlencode($this->API_VERSION)."&PWD=".urlencode($this->API_PASSWORD)."&USER=".urlencode($this->API_USERNAME)."&SIGNATURE=".urlencode($this->API_SIGNATURE).$nvpStr;
		curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);
		$response = curl_exec($ch);
		$nvpResArray=$this->deformatNVP($response);
		$nvpReqArray=$this->deformatNVP($nvpreq);
		$_SESSION['nvpReqArray']=$nvpReqArray;
		if (curl_errno($ch))
		{
			die("CURL send a error during perform operation: ".curl_errno($ch));
		} 
		else 
		{
			curl_close($ch);
		}

		return $nvpResArray;
	}

	function deformatNVP($nvpstr)
	{

		$intial=0;
		$nvpArray = array();
		while(strlen($nvpstr))
		{
			$keypos= strpos($nvpstr,'='); 
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr); 
			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
		 }
		return $nvpArray;
	}
	
}