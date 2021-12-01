<?php
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
 * @copyright  MAGIX CMS Copyright (c) 2008 - 2015 Gerits Aurelien,
 * http://www.magix-cms.com,  http://www.magix-cjquery.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 3.0.0
 * Author: Salvatore Di Salvo
 * @name plugins_faq_public
 */
class plugins_faq_public extends plugins_faq_db {
    /**
     * @var frontend_model_template $template
     * @var frontend_model_data $data
     * @var string $lang
     */
    protected
        $template,
        $data,
        $lang;

    /**
     * @var string $id
     */
    public $id;

    /**
     * Class constructor
     */
    public function __construct() {
        $this->template = new frontend_model_template();
		$this->data = new frontend_model_data($this);
		$this->lang = $this->template->currentLanguage();

        if(http_request::isGet('id')) $this->id = form_inputEscape::numeric($_GET['id']);
    }

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string|null $context
	 * @param bool $assign
	 * @return mixed
	 */
	private function getItems(string $type, $id = null, string $context = null, bool $assign = true) {
		return $this->data->getItems($type, $id, $context, $assign);
	}

	/**
	 * @param array $data
	 * @return array
	 */
	private function setQAData(array $data): array {
		$arr = [];
		if(!empty($data)) {
			foreach ($data as $row) {
				$arr[$row['id_qa']] = [
					'id_qa' => $row['id_qa'],
					'id_lang' => $row['id_lang'],
					'url' => $row['url_qa'],
					'title' => $row['title_qa'],
					'content' => $row['desc_qa']
				];
			}
		}
		return $arr;
	}

	/**
	 * @return array
	 */
	public function getPageContent(): array {
		$page = $this->getItems('page',['lang' => $this->lang],'one', false);
		return empty($page) ? [] : $page;
	}

	/**
     * @param int|string $id
	 * @return array
	 */
	public function getQAContent($id): array {
		$QA = $this->getItems('QA',['id' => $id, 'lang' => $this->lang],'one', false);
		return empty($QA) ? [] : $QA;
	}

	/**
	 * @return array
	 */
	public function getQAs(): array {
		$qas = $this->getItems('activeQAs',['lang' => $this->lang],'all', false);
		return empty($qas) ? [] : $this->setQAData($qas);
	}

    /**
     * Set breadcrumb information
     * @param array $config
     */
    public function setBreadcrumb(array $config) {
        $iso = $this->lang;
        $breadplugin = [];
        $breadplugin[] = ['name' => $this->template->getConfigVars('faq')];

        if($this->id && $config['mode_faq'] === 'pages') {
            $breadplugin[0]['url'] = http_url::getUrl().'/'.$iso.'/faq/';
            $breadplugin[0]['title'] = $this->template->getConfigVars('faq');
            $dataPage = $this->getItems('QA',['id' => $this->id, 'lang' => $iso],'one',false);
            if($dataPage) $breadplugin[] = ['name' => $dataPage['title_qa']];
        }

        $this->template->assign('breadplugin', $breadplugin);
    }

    /**
	 *
     */
    public function run() {
        $this->template->configLoad();
        $config = $this->getItems('faq_config',null,'one');
        $this->setBreadcrumb($config);
        if(isset($this->id) && $config['mode_faq'] === 'pages') {
            $langs = $this->getItems('qaActiveContent',$this->id,'all',false);
            $hreflang = [];
            if(!empty($langs)) {
                foreach ($langs as $row) {
                    $hreflang[$row['id_lang']] = '/'.$row['iso_lang'].'/faq/'.$row['id_qa'].'-'.$row['url_qa'].'/';
                }
            }
            $this->template->assign('hreflang',$hreflang,true);
            $this->template->assign('QA',$this->getQAContent($this->id));
            $this->template->display('faq/qa.tpl');
        }
        else {
            if(isset($this->id)) {
                $_GET['open'] = $this->id;
                $this->template->assign('canonical',"/$this->lang/faq/");
            }
            $this->template->assign('page',$this->getPageContent());
            $this->template->assign('QAs',$this->getQAs());
            $this->template->display('faq/index.tpl');
        }
    }
}