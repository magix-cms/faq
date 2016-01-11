<?php
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2013 magix-cms.com <support@magix-cms.com>
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

 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------

 # DISCLAIMER

 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
 /**
 * MAGIX CMS
 * @category   advantage
 * @package    plugins
 * @copyright  MAGIX CMS Copyright (c) 2008 - 2015 Gerits Aurelien,
 * http://www.magix-cms.com,  http://www.magix-cjquery.com
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    2.0
 * Author: Salvatore Di Salvo
 * Date: 16-12-15
 * Time: 14:00
 * @name advantage
 * Le plugin advantage
 */
class plugins_faq_admin extends DBfaq{
    /**
	 * 
	 * @var idadmin
	 */
	public $idadmin;
	/**
	 * 
	 * @var idlang
	 */
	public $idlang;
	/**
	 * Les variables globales
	 */
	public $action,$tab,$getlang,$edit,$message;
	public $title, $content, $idqa, $order;
	public static $notify = array('plugin'=>'true','template'=>'message-faq.tpl','method'=>'display','assignFetch'=>'notifier');

    /**
	 * Construct class
	 */
	public function __construct(){
        if(class_exists('backend_model_message')){
            $this->message = new backend_model_message();
        }
        
        if(magixcjquery_filter_request::isGet('action')){
            $this->action = magixcjquery_form_helpersforms::inputClean($_GET['action']);
        }
        if(magixcjquery_filter_request::isGet('tab')){
            $this->tab = magixcjquery_form_helpersforms::inputClean($_GET['tab']);
        }
        if(magixcjquery_filter_request::isGet('getlang')){
            $this->getlang = magixcjquery_form_helpersforms::inputNumeric($_GET['getlang']);
        }
		if(magixcjquery_filter_request::isGet('edit')){
            $this->edit = magixcjquery_form_helpersforms::inputNumeric($_GET['edit']);
        }

		# ADD PAGE
		if(magixcjquery_filter_request::isPost('title')){
			$this->title = magixcjquery_form_helpersforms::inputClean($_POST['title']);
		}
		if(magixcjquery_filter_request::isPost('content')){
			$this->content = magixcjquery_form_helpersforms::inputCleanQuote($_POST['content']);
		}

		# EDIT PAGE
		if(magixcjquery_filter_request::isPOST('idqa')){
			$this->idqa = magixcjquery_form_helpersforms::inputNumeric($_POST['idqa']);
		}

		# DELETE PAGE
		if(magixcjquery_filter_request::isPOST('delete')){
			$this->delete = magixcjquery_form_helpersforms::inputNumeric($_POST['delete']);
		}

		# ORDER PAGE
		if(magixcjquery_filter_request::isPost('order')){
			$this->order = magixcjquery_form_helpersforms::arrayClean($_POST['order']);
		}

		$this->template = new backend_controller_plugins();
	}

	/**
	 * Retourne le message de notification
	 * @param $type
	 */
	private function notify($type){
		$this->message->getNotify($type,self::$notify);
	}

	/**
	 * @access private
	 * Installation des tables mysql du plugin
	 */
	private function install_table(){
		if(parent::c_show_table() == 0){
			$this->template->db_install_table('db.sql', 'request/install.tpl');
		}else{
			return true;
		}
	}

	/**
	 *
	 */
	public function save($type)
	{
		if( !empty($this->title) ){
			$page = array(
				'idlang'	=> $this->getlang,
				'title' 	=> $this->title,
				'content' 	=> ( (isset($this->content) && !empty($this->content))?$this->content:NULL )
			);

			switch ($type) {
				case 'add':
					$c = parent::c_qa($this->getlang);
					if ($c != null)
						$page['qaorder'] = $c['nb'];
					else
						$page['qaorder'] = 0;
					parent::i_qa($page);
					break;
				case 'update':
					$page['id'] = $this->idqa;
					parent::u_qa($page);
					break;
			}

			$this->notify('save');
		}
	}

	/**
	 *
	 */
	public function del()
	{
		parent::d_qa($this->delete);
		$this->notify('delete');
	}

	/**
	 * Execute Update AJAX FOR order
	 * @access private
	 *
	 */
	private function update_order(){
		if(isset($this->order)){
			$p = $this->order;
			for ($i = 0; $i < count($p); $i++) {
				parent::u_order($i,$p[$i]);
			}
		}
	}

	/**
	 * Affiche les pages de l'administration du plugin
	 * @access public
	 */
	public function run(){
		if(self::install_table() == true){
			if (isset($this->tab) && $this->tab == 'about')
			{
				$this->template->display('about.tpl');
			}
			elseif (!isset($this->tab) || (isset($this->tab) && $this->tab == 'index'))
			{
				if(isset($this->action)) {
					if ($this->action == 'add') {
						$lang = parent::getLang($this->getlang);
						$this->template->assign('iso',$lang['iso']);
						$this->template->display('page/addpage.tpl');
					} elseif ($this->action == 'edit') {
						if ( isset($this->title) ) {
							if ( isset($this->idqa) && is_numeric($this->idqa) ) {
								$this->save('update');
							} else {
								$this->save('add');
							}
						} elseif ( isset($this->edit) && is_numeric($this->edit) ) {
							$this->template->assign('qa',parent::g_qa($this->edit));
							$this->template->display('page/editpage.tpl');
						}
					} elseif ($this->action == 'delete') {
						if ( isset($this->delete) && is_numeric($this->delete) ) {
							$this->del();
						}
					} elseif ($this->action == 'order' && isset($this->order)) {
						//var_dump($this->order);
						$this->update_order();
					} elseif ($this->action == 'getlast') {
						$last = parent::getLastQA($this->getlang);
						echo $last['id'];
					} elseif ($this->action == 'list') {
						$this->template->assign('pages',parent::getQA($this->getlang));
						$this->template->display('index.tpl');
					}
				}
			}
		}
	}
	
	/**
     * @access public
     * Options de reecriture des métas
     */
    public function seo_options(){
        return $options_string = array(
            'plugins'=>true
        );
    }

    /**
     * Set Configuration pour le menu
     * @return array
     */
    public function setConfig(){
        return array(
            'url'=> array(
                'lang'=>'list',
                'action'=>'list',
                'name'=>'FAQ'
            )
        );
    }

	//SITEMAP
	/*private function lastmod_dateFormat(){
		$dateformat = new magixglobal_model_dateformat();
		return $dateformat->sitemap_lastmod_dateFormat();
	}*/
	/**
	 * @access public
	 * Options de reecriture des sitemaps NEWS
	 */
	/*public function sitemap_rewrite_options(){
		return $options_string = array(
				'index'=>true,
				'level1'=>false,
				'level2'=>false,
				'records'=>false
		);
	}*/

	/**
	 * URL index du module suivant la langue
	 * @param $idlang
	 */
	/*public function sitemap_uri_index($idlang){
		$sitemap = new magixcjquery_xml_sitemap();
		// Table des langues
		$lang = new backend_db_block_lang();
		// Retourne le code ISO
		$db = $lang->s_data_iso($idlang);
		if($db != null){
			$sitemap->writeMakeNode(
					magixcjquery_html_helpersHtml::getUrl().magixglobal_model_rewrite::filter_plugins_root_url(
							$db['iso'],
							'about',
							true)
					,
					$this->lastmod_dateFormat(),
					'always',
					0.7
			);
		}
	}*/
}
class DBfaq{
    /**
	 * Vérifie si les tables du plugin sont installé
	 * @access protected
	 * return integer
	 */
	protected function c_show_table(){
		$table = 'mc_plugins_faq';
		return magixglobal_model_db::layerDB()->showTable($table);
	}

	// GET
	/**
	 * @param $id
	 * @return array
	 */
	protected function getLang($id)
	{
		$query = "SELECT iso FROM mc_lang WHERE idlang = :id";

		return magixglobal_model_db::layerDB()->selectOne($query,array(':id'=>$id));
	}

	/**
	 * @param $idlang
	 * @return array
	 */
	protected function getQA($idlang)
	{
		$query = "SELECT idqa as id, title, content FROM mc_plugins_faq WHERE idlang = :idlang ORDER BY qaorder";

		return magixglobal_model_db::layerDB()->select($query, array(
			':idlang' => $idlang
		));
	}

	/**
	 * @return array
	 */
	protected function getLastQA($idlang)
	{
		$query = "SELECT idqa as id FROM `mc_plugins_faq` WHERE idlang = :idlang ORDER BY idqa DESC LIMIT 1";

		return magixglobal_model_db::layerDB()->selectOne($query, array(
				':idlang' => $idlang
		));
	}

	/**
	 * @param $id
	 * @return array
	 */
	protected function g_qa($id)
	{
		$query = "SELECT iso, idqa as id, title, content
				FROM mc_plugins_faq
				JOIN mc_lang USING(idlang)
				WHERE idqa = :id";

		return magixglobal_model_db::layerDB()->selectOne($query, array(
				':id' => $id
		));
	}

	/**
	 * @param $idlang
	 * @return array
	 */
	protected function c_qa($idlang)
	{
		$query = "SELECT COUNT(idqa) as nb FROM mc_plugins_faq WHERE idlang = :idlang";

		return magixglobal_model_db::layerDB()->selectOne($query, array(
			':idlang' => $idlang
		));
	}

	// INSERT
	/**
	 * @param $page
	 */
	protected function i_qa($page)
	{
		$query = "INSERT INTO mc_plugins_faq (idlang,title,content,qaorder) VALUES (:idlang,:title,:content,:qaorder)";

		magixglobal_model_db::layerDB()->insert($query,array(
			':idlang'	=> $page['idlang'],
			':title'	=> $page['title'],
			':content'	=> $page['content'],
			':qaorder'	=> $page['qaorder']
		));
	}

	// UPDATE
	/**
	 * @param $page
	 */
	protected function u_qa($page)
	{
		$query = "UPDATE mc_plugins_faq
				  SET
					idlang = :idlang,
					title = :title,
					content = :content
				  WHERE idqa = :id";

		magixglobal_model_db::layerDB()->insert($query,array(
				':id'		=> $page['id'],
				':idlang'	=> $page['idlang'],
				':title'	=> $page['title'],
				':content'	=> $page['content']
		));
	}

	/**
	 * Met à jour l'ordre d'affichage des pages
	 * @param $i
	 * @param $id
	 */
	protected function u_order($i,$id){
		$sql = 'UPDATE mc_plugins_faq SET qaorder = :i WHERE idqa = :id';
		magixglobal_model_db::layerDB()->update($sql,
			array(
				':i'=>$i,
				':id'=>$id
			)
		);
	}

	// DELETE
	/**
	 * @param $id
	 */
	protected function d_qa($id)
	{
		$query = "DELETE FROM mc_plugins_faq WHERE idqa = :id";

		magixglobal_model_db::layerDB()->delete($query,array(':id'=>$id));
	}
}
?>