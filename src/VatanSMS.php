<?php 

	namespace Atiksoftware\SMS;
	
	class VatanSMS
	{

 
		private $_customerNo = "";
		private $_username = "";
		private $_password = "";
		private $_title = "";
		private $_smsType = "Normal";

		private $_smsPool = [];



		function __construct($customerNo = "", $username = "", $password = ""){

		}

		function setCustomerNo($e){
			$this->_customerNo = $e; 
			return $this;
		}
		function setUsername($e){
			$this->_username = $e; 
			return $this;
		}
		function setPassword($e){
			$this->_password = $e; 
			return $this;
		}
		function setTitle($e){
			$this->_title = $e; 
			return $this;
		}

		function setSmsType($e){
			if($e == "Normal" || $e == "Turkce"){
				$this->_smsType = $e; 
			}
			else{
				throw new Exception('SmsType can be only Normal or Turkce. SmsType yanlızca Normal veya Turkce olabilir');
			}
			return $this;
		}

		/**
		 * array or string convert to array
		 *
		 * @param string|array Number(s) | Numara(lar) 
		 *
		 * @return array
		 */
		function buildNumbers($numbers){
			$list = [];
			if(!is_array($numbers)){
				$numbers = [$numbers];
			}
			if(is_array($numbers)){
				foreach($numbers as $number){
					$nums = array_filter(explode(",",$number));
					foreach($nums as $num){
						$list[] = trim($num);
					}
				}
			}
			return $list;
		}
		function buildXML($key,$param){
			$data = "";
			$data .= "<{$key}>";
			if(is_array($param)){
				foreach($param as $k => $v){
					$data .= $this->buildXML($k,$v); 
				}
			} else {
				$data .= $param; 
			}
			$data .= "</{$key}>";
			return $data;
		}



		/**
		 * Clear list of created sms
		 * Oluşturulmuş sms listesini temizle
		 *
		 * @return this
		 */
		function clear(){
			$this->_smsPool = [];
			return $this;
		}

		/** 
		 * create a new sms and add to wait list for will send
		 * thats will wait until use $vatanSMS->send(). because call oncle Api Service for all sms
		 * 
		 * yeni bir sms oluştur ve gönderilmek üzere listeye ekle
		 * $vatanSMS->send() fonksiyonunu çağırana dek listede bekleyecektir. Tüm oluşturulmuş smsler için Api Servisi tek sefer çalıştırmak için. 
		 *
		 * @param string SMS Text | SMS Metni
		 * @param string|array Number(s) | Numara(lar) 
		 * @param string optional for future date | İleri tarihli gönderimler için
		 *
		 * @return this
		 */
		function sms($message, $numbers){
			$targets = $this->buildNumbers($numbers);
			foreach($targets as $target){
				$this->_smsPool[] = [
					"mesaj" => $message,
					"tel" => $target,
				];
			} 
			return $this;
		}


		/** 
		 * send for all created sms
		 * oluşturulmuş tüm smsleri gönder
		 */
		function send(){
			$params = [
				"kno" => $this->_customerNo,
				"kulad" => $this->_username,
				"sifre" => $this->_password,
				"gonderen" => $this->_title,
				"tur" => $this->_smsType,
				"telmesajlar" => ""
			];
			foreach($this->_smsPool as $sms){
				$params["telmesajlar"] .= $xmlData = $this->buildXML("telmesaj",$sms);
			}
			$xmlData = $this->buildXML("sms",$params);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://panel.vatansms.com/panel/smsgonderNNpost.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "data=$xmlData");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			$response = curl_exec($ch);
			curl_close($ch);
echo $response;
			return $this;
		}


	}
 