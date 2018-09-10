<?php
class ControllerExtensionModulePodSso extends Controller {
	public function index() {
		$this->load->language('extension/module/pod_sso');

		$data['client_id'] = $this->config->get('module_pod_sso_client_id');
		$data['client_secret'] = $this->config->get('module_pod_sso_client_secret');
		$data['sso_address'] = $this->config->get('module_pod_sso_address');
		$data['platform_address'] = $this->config->get('module_pod_sso_platform_address');
		$data['api_token'] = $this->config->get('module_pod_sso_api_token');
		$data['private_address'] = $this->config->get('module_pod_sso_private_address');
		$redirect_uri = $this->url->link('extension/module/pod_sso');

		if(!isset($this->request->get['code'])){

			$this->response->redirect("{$data['sso_address']}/oauth2/authorize/?client_id={$data['client_id']}&response_type=code&redirect_uri={$redirect_uri}&scope=profile email");

		}
		else {
			$code = $this->request->get['code'];
			$url = $data['sso_address']."/oauth2/token/";
			$client_id = $data['client_id'];
			$client_secret = $data['client_secret'];

			$ch = curl_init($url);
			$fields = "client_id={$client_id}&client_secret={$client_secret}&code={$code}&redirect_uri={$redirect_uri}&grant_type=authorization_code";
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$response = curl_exec($ch);
			$e = curl_error($ch);
			curl_close($ch);
			$token = json_decode($response);
			$this->session->data['access_token']= $token->access_token;
			$this->session->data['refresh_token']= $token->refresh_token;
			$this->session->data['expires_in']= $token->expires_in;
			$this->session->data['start_time']= time();
			$user_data = $this->getUserData();

			$this->session->data['userId'] = $user_data->userId;
			$this->session->data['bizId'] = $this->getBusinessId();
			$this->loginUser($user_data);
			$this->response->redirect($this->url->link('account/account'));
		}
	}
	public function getUserData()
	{
		$data['client_id'] = $this->config->get('module_pod_sso_client_id');
		$data['client_secret'] = $this->config->get('module_pod_sso_client_secret');
		$data['sso_address'] = $this->config->get('module_pod_sso_address');
		$data['platform_address'] = $this->config->get('module_pod_sso_platform_address');
		$data['api_token'] = $this->config->get('module_pod_sso_api_token');
		$data['private_address'] = $this->config->get('module_pod_sso_private_address');
		$access_token = $this->session->data['access_token'];
		$api_url = $data['platform_address'];

		$ch = curl_init($api_url.'/nzh/getUserProfile/');
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			"_token_: {$access_token}",
			"_token_issuer_: 1"
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);
		if ($err) {
			echo 'cURL Error #:' . $err;
			return false;
		} else {
			$resp = json_decode($response);
			return $resp->result;
		}
	}
	function getBusinessId(){
		$data['client_id']        = $this->config->get('module_pod_sso_client_id');
		$data['client_secret']    = $this->config->get('module_pod_sso_client_secret');
		$data['sso_address']      = $this->config->get('module_pod_sso_address');
		$data['platform_address'] = $this->config->get('module_pod_sso_platform_address');
		$data['api_token']        = $this->config->get('module_pod_sso_api_token');
		$data['private_address']  = $this->config->get('module_pod_sso_private_address');
		$curl = curl_init();
		$api_url = $data['platform_address'];
		$api_token = $data['api_token'];
		curl_setopt_array($curl, [
			CURLOPT_URL => $api_url . "/nzh/getUserBusiness",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => [
				"_token_: {$api_token}",
				"_token_issuer_: 1"
			],
		]);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			return false;
		} else {
			return json_decode($response)->result->id;
		}
	}


	public function loginUser($user_data){

		$this->load->model('account/customer');
		$data = [
			'firstname' => isset($user_data->firstName)? $user_data->firstName : '',
			'lastname'  => isset($user_data->lastName)? $user_data->lastName : '',
			'email' => $user_data->email,
			'telephone' => '',
			'password' => 'secret123'
		];
		$k = $this->model_account_customer->addCustomer($data);
		echo "<pre>";
		print_r($k);
		exit();
		return true;
	}
}