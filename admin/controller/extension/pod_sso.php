<?php
class ControllerExtensionModulePodSso extends Controller {
	private $error = array();

	public function index() {

		$this->load->language('extension/module/pod_sso');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_pod_sso', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['pod_sso_client_id'])) {
			$data['error_client_id'] = $this->error['pod_sso_client_id'];
		} else {
			$data['error_client_id'] = '';
		}

		if (isset($this->error['pod_sso_client_secret'])) {
			$data['error_client_secret'] = $this->error['pod_sso_client_secret'];
		} else {
			$data['error_client_secret'] = '';
		}
		if (isset($this->error['pod_sso_address'])) {
			$data['error_sso_address'] = $this->error['pod_sso_address'];
		} else {
			$data['error_sso_address'] = '';
		}
		if (isset($this->error['pod_sso_platform_address'])) {
			$data['error_platform_address'] = $this->error['pod_sso_platform_address'];
		} else {
			$data['error_platform_address'] = '';
		}
		if (isset($this->error['pod_sso_api_token'])) {
			$data['error_api_token'] = $this->error['pod_sso_api_token'];
		} else {
			$data['error_api_token'] = '';
		}
		if (isset($this->error['pod_sso_private_address'])) {
			$data['error_private_address'] = $this->error['pod_sso_private_address'];
		} else {
			$data['error_private_address'] = '';
		}
		if (isset($this->error['pod_sso_guild_code'])) {
			$data['error_guild_code'] = $this->error['pod_sso_guild_code'];
		} else {
			$data['error_guild_code'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
			);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
			);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/pod_sso', 'user_token=' . $this->session->data['user_token'], true)
			);

		$data['action'] = $this->url->link('extension/module/pod_sso', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);		


		if(isset($this->request->post['module_pod_sso_client_id'])) {
			$data['module_pod_sso_client_id'] = $this->request->post['module_pod_sso_client_id'];
		} elseif ($this->config->get('module_pod_sso_client_id')){
			$data['module_pod_sso_client_id'] = $this->config->get('module_pod_sso_client_id');
		} else{
			$data['module_pod_sso_client_id'] = '';
		}
		if(isset($this->request->post['module_pod_sso_client_secret'])) {
			$data['module_pod_sso_client_secret'] = $this->request->post['module_pod_sso_client_secret'];
		} elseif ($this->config->get('module_pod_sso_client_secret')){
			$data['module_pod_sso_client_secret'] = $this->config->get('module_pod_sso_client_secret');
		} else{
			$data['module_pod_sso_client_secret'] = '';
		}

		if(isset($this->request->post['module_pod_sso_address'])) {
			$data['module_pod_sso_address'] = $this->request->post['module_pod_sso_address'];
		} elseif ($this->config->get('module_pod_sso_address')){
			$data['module_pod_sso_address'] = $this->config->get('module_pod_sso_address');
		} else{
			$data['module_pod_sso_address'] = '';
		}

		if(isset($this->request->post['module_pod_sso_platform_address'])) {
			$data['module_pod_sso_platform_address'] = $this->request->post['module_pod_sso_platform_address'];
		} elseif ($this->config->get('module_pod_sso_platform_address')){
			$data['module_pod_sso_platform_address'] = $this->config->get('module_pod_sso_platform_address');
		} else{
			$data['module_pod_sso_platform_address'] = '';
		}

		if(isset($this->request->post['module_pod_sso_api_token'])) {
			$data['module_pod_sso_api_token'] = $this->request->post['module_pod_sso_api_token'];
		} elseif ($this->config->get('module_pod_sso_api_token')){
			$data['module_pod_sso_api_token'] = $this->config->get('module_pod_sso_api_token');
		} else{
			$data['module_pod_sso_api_token'] = '';
		}

		if(isset($this->request->post['module_pod_sso_private_address'])) {
			$data['module_pod_sso_private_address'] = $this->request->post['module_pod_sso_private_address'];
		} elseif ($this->config->get('module_pod_sso_private_address')){
			$data['module_pod_sso_private_address'] = $this->config->get('module_pod_sso_private_address');
		} else{
			$data['module_pod_sso_private_address'] = '';
		}

		if(isset($this->request->post['module_pod_sso_guild_code'])) {
			$data['module_pod_sso_guild_code'] = $this->request->post['module_pod_sso_guild_code'];
		} elseif ($this->config->get('module_pod_sso_guild_code')){
			$data['module_pod_sso_guild_code'] = $this->config->get('module_pod_sso_guild_code');
		} else{
			$data['module_pod_sso_guild_code'] = '';
		}

		if (isset($this->request->post['module_pod_sso_status'])) {
			$data['module_pod_sso_status'] = $this->request->post['module_pod_sso_status'];
		} elseif ($this->config->get('module_pod_sso_status')) {
			$data['module_pod_sso_status'] = $this->config->get('module_pod_sso_status');
		} else {
			$data['module_pod_sso_status'] = 0;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->config->set('template_cache', false);
		$this->response->setOutput($this->load->view('extension/module/pod_sso', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/pod_sso')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->request->post['module_pod_sso_client_id']) {
			$this->error['pod_sso_client_id'] = $this->language->get('error_client_id');
		}
		if (!$this->request->post['module_pod_sso_client_secret']) {
			$this->error['pod_sso_client_secret'] = $this->language->get('error_client_secret');
		}
		if (!$this->request->post['module_pod_sso_address']) {
			$this->error['pod_sso_address'] = $this->language->get('error_sso_address');
		}
		if (!$this->request->post['module_pod_sso_platform_address']) {
			$this->error['pod_sso_platform_address'] = $this->language->get('error_platform_address');
		}
		if (!$this->request->post['module_pod_sso_api_token']) {
			$this->error['pod_sso_api_token'] = $this->language->get('error_api_token');
		}
		if (!$this->request->post['module_pod_sso_private_address']) {
			$this->error['pod_sso_private_address'] = $this->language->get('error_private_address');
		}
		if (!$this->request->post['module_pod_sso_guild_code']) {
			$this->error['pod_sso_guild_code'] = $this->language->get('error_guild_code');
		}



		return !$this->error;
	}
}