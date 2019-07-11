<?php
class ControllerExtensionModuleAccessible extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/accessible');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_accessible', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/accessible', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/accessible', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/accessible', $data));
	}
	
	public function install() {
		// now add triggers
		$this->load->model('setting/event');
		$this->model_setting_event->addEvent('accessible','catalog/view/common/header/before','event/accessible/language');
		//$this->model_setting_event->addEvent('accessible','catalog/view/common/header/after','event/accessible/view');
	}

	public function uninstall() {
		// remove evant triggers
		$this->load->model('setting/event');
		$this->model_setting_event->deleteEventByCode('accessible');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/accessible')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}