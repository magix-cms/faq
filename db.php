<?php
class plugins_faq_db {
	/**
	 * @param array $config
	 * @param array|null $params
	 * @return mixed|null
	 * @throws Exception
	 */
	public function fetchData(array $config, $params = []) {
		$sql = '';

		if ($config['context'] === 'all') {
			switch ($config['type']) {
				case 'pages':
					$sql = 'SELECT *
							FROM mc_faq mf
							JOIN mc_faq_content mfc ON(mf.id_page = mfc.id_page)
							JOIN mc_lang ml ON(mfc.id_lang = ml.id_lang)
							WHERE mf.id_page = :id';
					break;
				case 'qas':
					$sql = 'SELECT 
								mq.id_qa,
								mqc.url_qa,
								mqc.title_qa,
								mqc.desc_qa
							FROM mc_qa mq
							JOIN mc_qa_content mqc ON(mq.id_qa = mqc.id_qa)
							JOIN mc_lang ml ON(mqc.id_lang = ml.id_lang)
							WHERE mqc.id_lang = :default_lang
							ORDER BY order_qa';
					break;
				case 'activeQAs':
					$sql = 'SELECT 
								mq.id_qa,
								mqc.url_qa,
								mqc.title_qa,
								mqc.desc_qa
							FROM mc_qa mq
							JOIN mc_qa_content mqc ON(mq.id_qa = mqc.id_qa)
							JOIN mc_lang ml ON(mqc.id_lang = ml.id_lang)
							WHERE ml.iso_lang = :lang
							AND mqc.published_qa = 1
							ORDER BY order_qa';
					break;
				case 'qa':
					$sql = 'SELECT *
							FROM mc_qa mq
							JOIN mc_qa_content mqc ON(mq.id_qa = mqc.id_qa)
							JOIN mc_lang ml ON(mqc.id_lang = ml.id_lang)
							WHERE mqc.id_lang = :default_lang';
					break;
				case 'qaContent':
					$sql = 'SELECT *
							FROM mc_qa mq
							JOIN mc_qa_content mqc ON(mq.id_qa = mqc.id_qa)
							JOIN mc_lang ml ON(mqc.id_lang = ml.id_lang)
							WHERE mq.id_qa = :id';
					break;
				case 'qaActiveContent':
					$sql = 'SELECT *
							FROM mc_qa mq
							JOIN mc_qa_content mqc ON(mq.id_qa = mqc.id_qa)
							JOIN mc_lang ml ON(mqc.id_lang = ml.id_lang)
							WHERE mq.id_qa = :id
							AND mqc.published_qa = 1';
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
                case 'faq_config':
                    $sql = 'SELECT * FROM mc_faq_config ORDER BY id_config DESC LIMIT 0,1';
                    break;
				case 'root_page':
					$sql = 'SELECT * FROM mc_faq ORDER BY id_page DESC LIMIT 0,1';
					break;
				case 'content_page':
					$sql = 'SELECT * FROM mc_faq_content WHERE id_page = :id AND id_lang = :id_lang';
					break;
				case 'page':
					$sql = 'SELECT *
							FROM mc_faq mf
							JOIN mc_faq_content mfc ON(mf.id_page = mfc.id_page)
							JOIN mc_lang ml ON(mfc.id_lang = ml.id_lang)
							WHERE ml.iso_lang = :lang
							LIMIT 0,1';
					break;
				case 'QA':
					$sql = 'SELECT 
                                mqc.id_qa,
                                mqc.title_qa,
                                mqc.url_qa,
                                mqc.desc_qa,
                                ml.iso_lang
							FROM mc_qa mq
							JOIN mc_qa_content mqc ON(mq.id_qa = mqc.id_qa)
							JOIN mc_lang ml ON(mqc.id_lang = ml.id_lang)
							WHERE ml.iso_lang = :lang
							AND mqc.id_qa = :id';
					break;
				case 'qaContent':
					$sql = 'SELECT * FROM mc_qa_content WHERE id_qa = :id AND id_lang = :id_lang';
					break;
				case 'lastQA':
					$sql = 'SELECT * FROM mc_qa ORDER BY id_qa DESC LIMIT 0,1';
					break;
			}

			return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
		}
	}

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function insert(array $config, array $params = []) {
		$sql = '';

		switch ($config['type']) {
			case 'root_page':
				$sql = 'INSERT INTO mc_faq(date_register) VALUES (NOW())';
				break;
			case 'content_page':
				$sql = 'INSERT INTO mc_faq_content(id_page, id_lang, name_page, content_page, seo_title_page, seo_desc_page, published_page) 
				  			VALUES (:id, :id_lang, :name_page, :content_page, :seo_title_page, :seo_desc_page, :published_page)';
				break;
			case 'qa':
				$sql = 'INSERT INTO mc_qa (order_qa, date_register)  
						SELECT COUNT(id_qa), NOW() FROM mc_qa';
				break;
			case 'qaContent':
				$sql = 'INSERT INTO mc_qa_content(id_qa, id_lang, title_qa, url_qa, desc_qa, seo_title_qa, seo_desc_qa, published_qa)
						VALUES (:id_qa, :id_lang, :title_qa, :url_qa, :desc_qa, :seo_title_qa, :seo_desc_qa, :published_qa)';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->insert($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function update(array $config, array $params = []) {
		$sql = '';

		switch ($config['type']) {
            case 'config':
                $sql = 'UPDATE mc_faq_config 
						SET 
							mode_faq = :mode_faq,
							accordion_mode = :accordion_mode
						WHERE id_config = :id_config';
                break;
			case 'content_page':
				$sql = 'UPDATE mc_faq_content 
						SET 
							name_page = :name_page,
							content_page = :content_page,
							seo_title_page = :seo_title_page,
							seo_desc_page = :seo_desc_page,
							published_page = :published_page
						WHERE id_page = :id 
						AND id_lang = :id_lang';
				break;
			case 'qaContent':
				$sql = 'UPDATE mc_qa_content
						SET 
							title_qa = :title_qa,
							url_qa = :url_qa,
							desc_qa = :desc_qa,
							seo_title_qa = :seo_title_qa,
							seo_desc_qa = :seo_desc_qa,
							published_qa = :published_qa
						WHERE id_content = :id 
						AND id_lang = :id_lang';
				break;
			case 'order':
				$sql = 'UPDATE mc_qa 
						SET order_qa = :order_qa
						WHERE id_qa = :id';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->update($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function delete(array $config, array $params = []) {
		$sql = '';

		switch ($config['type']) {
			case 'qa':
				$sql = 'DELETE FROM mc_qa WHERE id_qa = :id';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->delete($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}
}