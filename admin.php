<?php
require_once ('db.php');
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2021 magix-cms.com <support@magix-cms.com>
 #
 # OFFICIAL TEAM :
 #
 #   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
 #
 # Redistributions of files must retain the above copyright notice.
 # This program is free software: you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation, either version 3 of the License, or
 # (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU General Public License for more details.
 #
 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------
 #
 # DISCLAIMER
 #
 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
/**
 * MAGIX CMS
 * @category plugins
 * @package faq
 * @copyright MAGIX CMS Copyright (c) 2008 - 2015 Gerits Aurelien,
 * http://www.magix-cms.com,  http://www.magix-cjquery.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 3.0.0
 * Author: Salvatore Di Salvo
 * @name plugins_faq_admin
 */
class plugins_faq_admin extends plugins_faq_db {
	/**
	 * @var backend_model_template $template
	 * @var backend_controller_plugins $plugins
	 * @var component_core_message $message
	 * @var backend_model_language $modelLanguage
	 * @var component_collections_language $collectionLanguage
	 * @var backend_model_data $data
	 * @var backend_model_setting $settings
	 * @var http_header $header
	 * @var form_inputEscape $formClean
	 */
	protected
		$template,
		$plugins,
		$message,
		$modelLanguage,
		$collectionLanguage,
		$data,
		$settings,
		$header,
        $formClean;

	/**
	 * @var string $controller
	 */
	protected $controller;

	/**
	 * @var array $setting
	 */
	protected $setting;

	/**
	 * @var string $action
	 * @var string $tabs
	 */
	public
		$action = '',
		$tabs = '';

	/**
	 * @var int $edit
	 * @var int $id
	 */
	public
		$edit = 0,
		$id = 0;

	/**
	 * @var array $qa
	 * @var array $faq
	 * @var array $faq_config
	 * @var array $order
	 */
	public
		$qa = [],
		$faq = [],
		$faq_config = [],
		$order = [];

	/**
	 * Construct class
	 */
	public function __construct() {
		$this->template = new backend_model_template();
		$this->plugins = new backend_controller_plugins();
		$this->message = new component_core_message($this->template);
		$this->modelLanguage = new backend_model_language($this->template);
		$this->collectionLanguage = new component_collections_language();
		$this->data = new backend_model_data($this);
		$this->settings = new backend_model_setting();
		$this->setting = $this->settings->getSetting();
		$this->header = new http_header();
        $this->formClean = new form_inputEscape();

		// --- GET
		if (http_request::isGet('controller')) $this->controller = $this->formClean->simpleClean($_GET['controller']);
		if (http_request::isGet('edit')) $this->edit = $this->formClean->numeric($_GET['edit']);
		if (http_request::isRequest('action')) $this->action = $this->formClean->simpleClean($_REQUEST['action']);
		if (http_request::isGet('tabs')) $this->tabs = $this->formClean->simpleClean($_GET['tabs']);
	}

	/**
	 * Method to override the name of the plugin in the admin menu
	 * @return string
	 */
	public function getExtensionName(): string {
		return $this->template->getConfigVars('faq_plugin');
	}

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string|null $context
	 * @param string|bool $assign
	 * @return mixed
	 */
	private function getItems(string $type, $id = null, string $context = null, $assign = true) {
		return $this->data->getItems($type, $id, $context, $assign);
	}

	/**
	 * @param array|null $data
	 * @return array
	 */
	private function setItemPageData($data): array {
		$arr = [];
		if(!empty($data)) {
			foreach ($data as $page) {
				if (!array_key_exists('id_page', $arr)) {
					$arr['id_page'] = $page['id_page'];
				}
				$arr['content'][$page['id_lang']] = [
					'id_lang'          => $page['id_lang'],
					'name_page'        => $page['name_page'],
					'content_page'     => $page['content_page'],
					'seo_title_page'   => $page['seo_title_page'],
					'seo_desc_page'    => $page['seo_desc_page'],
					'published_page'   => $page['published_page']
				];
			}
		}
		return $arr;
	}

	/**
	 * @param array $data
	 * @return array
	 */
	private function setQAData(array $data): array {
		$arr = [];
		if(!empty($data)) {
			foreach ($data as $qa) {
				if (!array_key_exists($qa['id_qa'], $arr)) {
					$arr[$qa['id_qa']] = [];
					$arr[$qa['id_qa']]['id_qa'] = $qa['id_qa'];
				}

				$arr[$qa['id_qa']]['content'][$qa['id_lang']] = [
					'id_lang' => $qa['id_lang'],
					'title_qa' => $qa['title_qa'],
					'desc_qa' => $qa['desc_qa'],
					'published_qa' => $qa['published_qa']
				];
			}
		}
		return $arr;
	}

	/**
	 * Insert data
	 * @param array $config
	 */
	private function add(array $config) {
		switch ($config['type']) {
			case 'qa':
			case 'qaContent':
			case 'root_page':
			case 'content_page':
				parent::insert(
					['type' => $config['type']],
					$config['data']
				);
				break;
		}
	}

	/**
	 * Update data
	 * @param array $config
	 */
	private function upd(array$config) {
		switch ($config['type']) {
			case 'config':
			case 'qa':
			case 'qaContent':
			case 'content':
			case 'content_page':
				parent::update(
					['type' => $config['type']],
					$config['data']
				);
				break;
		}
	}

	/**
	 * Delete a record
	 * @param array $config
	 */
	private function del(array $config) {
		switch ($config['type']) {
			case 'qa':
				parent::delete(
					['type' => $config['type']],
					$config['data']
				);
				break;
		}
	}

	/**
	 * Update order
	 */
	public function order() {
		$p = $this->order;
		for ($i = 0; $i < count($p); $i++) {
			parent::update(
				['type' => 'order'],
				[
					'id'    => $p[$i],
					'order_qa' => $i
				]
			);
		}
	}

	/**
	 * Execute the plugin
	 */
	public function run() {
		if($this->action) {
            // --- ADD or EDIT
            if (http_request::isPost('qa')) {
                $qa = $_POST['qa'];
                $content = $qa['content'];
                foreach($content as $key => $arr) {
                    foreach($arr as $k => $v) {
                        $content[$key][$k] = ($k === 'desc_qa') ? $this->formClean->cleanQuote($v) : $this->formClean->simpleClean($v);
                    }
                }
                $qa['content'] = $content;
                $this->qa = $qa;
            }
            if (http_request::isPost('content')) {
                $array = $_POST['content'];
                foreach($array as $key => $arr) {
                    foreach($arr as $k => $v) {
                        $array[$key][$k] = ($k == 'content_page') ? $this->formClean->cleanQuote($v) : $this->formClean->simpleClean($v);
                    }
                }
                $this->content = $array;
            }
            if (http_request::isPost('id')) $this->id = $this->formClean->simpleClean($_POST['id']);
            if (http_request::isPost('faq_config')) $this->faq_config = $this->formClean->arrayClean($_POST['faq_config']);

            // --- Order
            if (http_request::isPost('faq')) $this->order = $this->formClean->arrayClean($_POST['faq']);

			switch ($this->action) {
				case 'add':
				case 'edit':
					if(isset($this->tabs) && $this->tabs === 'content' && isset($this->content) && !empty($this->content)) {
						$root = $this->getItems('root_page',null,'one',false);
						if (!$root) {
							$this->add(['type' => 'root_page', 'data' => []]);
							$root = $this->getItems('root_page',null,'one',false);
						}
						$id = $root['id_page'];

						foreach ($this->content as $lang => $content) {
							if(empty($content['id'])) $content['id'] = $id;
							$rootLang = $this->getItems('content_page',['id' => $id,'id_lang' => $lang],'one',false);

							$content['id_lang'] = $lang;
							$content['published_page'] = (!isset($content['published_page']) ? 0 : 1);

							$config = [
								'type' => 'content_page',
								'data' => $content
							];

							($rootLang) ? $this->upd($config) : $this->add($config);
						}
						$this->message->json_post_response(true,'update');
					}
					elseif(!empty($this->qa)) {
						$notify = 'update';

						if (!isset($this->qa['id_qa'])) {
							$this->add([
								'type' => 'qa',
								'data' => []
							]);

							$lastQA = $this->getItems('lastQA', null,'one',false);
							$this->qa['id_qa'] = $lastQA['id_qa'];
							$notify = 'add_redirect';
						}

						foreach ($this->qa['content'] as $lang => $qa) {
							$qa['id_lang'] = $lang;
							$qa['published_qa'] = (!isset($qa['published_qa']) ? 0 : 1);
							$qaLang = $this->getItems('qaContent',['id' => $this->qa['id_qa'],'id_lang' => $lang],'one',false);

							if(!empty($qaLang)) {
								$qa['id'] = $qaLang['id_content'];
							}
							else {
								$qa['id_qa'] = $this->qa['id_qa'];
							}
                            if (empty($qa['url_qa'])) {
                                $qa['url_qa'] = http_url::clean($qa['title_qa'],[
                                    'dot' => false,
                                    'ampersand' => 'strict',
                                    'cspec' => '', 'rspec' => ''
                                ]);
                            }

							$config = [
								'type' => 'qaContent',
								'data' => $qa
							];

							$qaLang ? $this->upd($config) : $this->add($config);
						}
						$this->message->json_post_response(true,$notify);
					}
                    elseif(isset($this->faq_config)) {
                        $this->faq_config['accordion_mode'] = !isset($this->faq_config['accordion_mode']) ? 0 : 1;
                        $this->upd([
                            'type' => 'config',
                            'data' => $this->faq_config
                        ]);
                        $this->message->json_post_response(true, 'update');
                    }
					else {
						$this->modelLanguage->getLanguage();

						if($this->edit) {
                            $this->getItems('faq_config',null,'one');
							$collection = $this->getItems('qaContent',$this->edit,'all',false);
							$setEditData = $this->setQAData($collection);
							$this->template->assign('qa', $setEditData[$this->edit]);
						}

						$this->template->assign('edit', $this->action === 'edit');
						$this->template->display('edit.tpl');
					}
					break;
				case 'delete':
					if(isset($this->id) && !empty($this->id)) {
						$this->del([
							'type' => 'qa',
							'data' => ['id' => $this->id]
						]);
						$this->message->json_post_response(true,'delete',['id' => $this->id]);
					}
					break;
				case 'order':
					if (isset($this->faq) && is_array($this->faq)) $this->order();
					break;
			}
		}
		else {
            $config = $this->getItems('faq_config',null,'one');
			$this->modelLanguage->getLanguage();
			$defaultLanguage = $this->collectionLanguage->fetchData(['context'=>'one','type'=>'default']);
			$last = $this->getItems('root_page',null,'one',false);
			$collection = $this->getItems('pages',$last['id_page'],'all',false);
			$this->template->assign('pages', $this->setItemPageData($collection));
			$this->getItems('qas',['default_lang'=>$defaultLanguage['id_lang']],'all');
			$assign = [
				'id_qa',
				'title_qa' => ['title' => 'name'],
				'desc_qa' => ['title' => 'content_qa', 'class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null]
			];
            $columns = ['id_qa','title_qa','desc_qa'];
			$this->data->getScheme(['mc_faq','mc_faq_content'],$columns,$assign);
			$this->template->display('index.tpl');
		}
	}
}