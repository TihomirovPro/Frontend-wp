<?php

/**
 * Plugin Name: Kama Breadcrumbs
 * Description: Хлебные крошки для WordPress. Плагин ничего не делает, а только подключает код, который затем можно использовать в теме. Чтобы вывести крошки в теме используйте такуй код: <code>&lt;?php echo kama_breadcrumbs(); ?&gt;</code>.
 * Plugin URI:  https://wp-kama.ru/?p=8396
 * Author:      Kama
 * Author URI:  http://wp-kama.ru/
 * Domain Path: /lang
 * Text Domain: bcrumbs
 * Version:     4.8.3
 *
 * PHP: 5.4+
 */


add_action( 'plugins_loaded', 'load_kama_breadcrumbs_textdomain' );
function load_kama_breadcrumbs_textdomain(){
	load_plugin_textdomain( 'bcrumbs', false, dirname( plugin_basename( __FILE__ ) ) .'/lang/' );
}


/**
 * Хлебные крошки для WordPress - kama breadcrumbs
 *
 * @param  array  $args  Опции. См. переменную $def_args
 * @param  array  $l10n  Для локализации. См. переменную $default_l10n.
 *
 * @return string Выводит на экран HTML код
 */
function kama_breadcrumbs( $args = [], $l10n = [] ){

	if( is_string( $args ) )
		$args = [ 'sep' => $args ];

	$kb = new Kama_Breadcrumbs;

	return $kb->get_crumbs( $args, $l10n );
}

class Kama_Breadcrumbs {

	public $arg;

	// Параметры по умолчанию
	static $def_args = [

		'sep'             => ' / ', // разделитель. Можно указать вместе с HTML оберткой: <span> > </span>
		'on_front_page'   => true,  // выводить крошки на главной странице
		'show_post_title' => true,  // показывать ли название записи в конце (последний элемент). Для записей, страниц, вложений
		'show_term_title' => true,  // показывать ли название элемента таксономии в конце (последний элемент). Для меток, рубрик и других такс

		'last_sep'        => true,  // показывать последний разделитель, когда заголовок в конце не отображается
		'nofollow'        => false, // добавлять rel=nofollow к ссылкам?

		'priority_tax'    => [],
							 // приоритетные таксономии для крошек у записей, нужно когда запись в нескольких таксах: array( 'category', 'tax_name' ).
							 // Порядок имеет значение: чем раньше тем важнее.
							 // В значениях можно указать приоритетные термины, тогда название таксономии нужно указывать в ключе.
							 // когда запись находится в нескольких элементах одной таксы одновременно.
							 // Например: array( 'category'=>array(45,'term_name','имя терма'), 'tax_name'=>array(1,2,'name') )
							 // 'category' - такса для которой указываются приор. элементы: 45 - ID термина, 'term_name' - ярлык или 'имя терма' - заголовок.
							 // порядок 45, 'term_name', 'имя терма' имеет значение: чем раньше тем важнее.
		                     // Все указанные термины важнее неуказанных...

		'disable_tax'     => [], // таксономии которые нужно исключить из показа в крошках у записей: array('post_tag', 'tax_name')
		'number_tax'      => [],
		                     // с версии 4.7. позволяет выводить в крошках для записей сразу несколько таксономий.
							 // Пример: array('post'=>2) - для типа записи post выведет крошки для двух таксономий (по умолчанию: category и post_tag).
							 // Поменять порядок также можно в параметре 'priority_tax'.

		'markup'          => 'Microdata',
							 // 'markup' - микроразметка. Может быть: 'Microdata', 'RDFa', '' - без микроразметки
							 // или можно указать свой массив разметки:
							 // array(
							 //     'wrappatt'  => '<div class="kama_breadcrumbs">%s</div>',
							 //     'linkpatt'  => '<a href="%s">%s</a>',
							 //     'titlepatt' => '<span class="kb_title">%s</span>',
							 //     'seppatt'   => '<span class="kb_sep">%s</span>'
							 // )
		'wrap_class'      => 'kama_breadcrumbs', // css класс для главного div
		'link_class'      => 'kb_link',          // css класс элемента ссылки
		'title_class'     => 'kb_title',         // css класс заголовка элемента
		'sep_class'       => 'kb_sep',           // css класс разделителя

		'use_the_title_filter' => false, // использовать ли фильтр 'the_title' для заголовоков записей. Может пригодится для плагинов мультиязычности.

		// служебные
		'pg_end'    => '',
	];

	function get_crumbs( $args, $l10n ){

		global $post, $wp_post_types;

		// Локализация
		$def_l10n = [
			'home'       => __( 'Главная',                                 'bcrumbs' ), // Главная
			'paged'      => __( 'Page %d',                              'bcrumbs' ), // Страница %d
			'_404'       => __( 'Error 404',                            'bcrumbs' ), // Ошибка 404
			'search'     => __( 'Search results by: <b>%s</b>',         'bcrumbs' ), // Результаты поиска по запросу - <b>%s</b>
			'author'     => __( 'Author archive: <b>%s</b>',            'bcrumbs' ), // Архив автора: <b>%s</b>
			'year'       => __( 'Archive for <b>%d</b> year',           'bcrumbs' ), // Архив за <b>%d</b> год
			'month'      => __( 'Archive for <b>%s</b>',                'bcrumbs' ), // Архив за <b>%d</b> месяц
			'day'        => __( 'Archive for <b>%1$s date</b>, %2$s',   'bcrumbs' ), // Архив за <b>%1$s число</b>, %2$s
			// day выведет: Архив за 5 число, среда
			'attachment' => __( 'Media: %s',                            'bcrumbs' ), // Медиа: %s
			'tag'        => __( 'Posts by tag: <b>%s</b>',              'bcrumbs' ), // Записи по метке: <b>%s</b>
			'tax_tag'    => __( '%1$s from "%2$s" by tag: <b>%3$s</b>', 'bcrumbs' ), // %1$s из "%2$s" по тегу: <b>%3$s</b>
			// tax_tag выведет: 'тип_записи из "название_таксы" по тегу: имя_термина'.
			// Если нужны отдельные холдеры, например только имя термина, пишем так: 'записи по тегу: %3$s'
		];

		// Фильтрует дефолты и сливает
		$loc       = (object) apply_filters( 'kama_breadcrumbs_l10n', array_merge( $def_l10n, $l10n ), $l10n );
		$this->arg = (object) apply_filters( 'kama_breadcrumbs_args', array_merge( self::$def_args, $args ), $args );

		$arg = & $this->arg; // упростим

		// микроразметка - доки: https://schema.org/BreadcrumbList
		if( 'микроразметка' ){

			// по умолчанию
			$markup = array_merge( [
				'wrappatt'  => '<div class="'. $arg->wrap_class .'">%s</div>',
				'linkpatt'  => '<a class="'. $arg->link_class .'" href="%s">%s</a>',
				'titlepatt' => '<span class="'. $arg->title_class .'">%s</span>',
				'seppatt'   => '<span class="'. $arg->sep_class .'">%s</span>',
			], (array) $arg->markup );

			// schema.org - Microdata
			if( 'Microdata' === $arg->markup ){
				$markup = array_merge( $markup, [
					'wrappatt'  => '<div class="'. $arg->wrap_class .'" itemscope itemtype="http://schema.org/BreadcrumbList">%s</div>',
					'linkpatt'  => '
						<span class="'. $arg->link_class .'" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
							<a href="%s" itemprop="item"><span itemprop="name">%s</span></a>
							<meta itemprop="position" content="ORDERNUM" />
						</span>',
					'titlepatt' => '
						<span class="'. $arg->title_class .'" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
							<span itemprop="name">%s</span>
							<meta itemprop="item" content="PERMALINK" />
							<meta itemprop="position" content="ORDERNUM" />
						</span>',
				] );
			}
			// schema.org - RDFa
			elseif( 'RDFa' === $arg->markup ){
				$markup = array_merge( $markup, [
					'wrappatt'  => '<div class="'. $arg->wrap_class .'" vocab="https://schema.org/" typeof="BreadcrumbList">%s</div>',
					'linkpatt'  => '
						<span class="'. $arg->link_class .'" property="itemListElement" typeof="ListItem">
							<a href="%s" property="item" typeof="WebPage"><span property="name">%s</span></a>
							<meta property="position" content="ORDERNUM" />
						</span>',
					// В крошках нужно указать item, name, position. A item требует URL, которого нет.
					// Поэтому не подключаем последний элемент в крошки
					'titlepatt' => '<span class="'. $arg->title_class .'"><span>%s</span></span>',
				] );
			}
			// data-vocabulary.org
			elseif( 'data-vocabulary.org' === $arg->markup ){
				$markup = array_merge( $markup, [
					'wrappatt'  => '<div class="'. $arg->wrap_class .'" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">%s</div>',
					'linkpatt'  => '<span class="'. $arg->link_class .'" itemprop="title"><a href="%s">%s</a></span>',
					'titlepatt' => '<span class="'. $arg->title_class .'" itemprop="title">%s</span>',
				] );
			}

			$arg->wrappatt  = $markup['wrappatt'];
			$arg->titlepatt = $markup['titlepatt'];
			$arg->linkpatt  = $arg->nofollow ? str_replace('<a ','<a rel="nofollow" ', $markup['linkpatt']) : $markup['linkpatt'];

			// если в разделителе указан HTML тег, то берем его как есть...
			$arg->sep = ( false !== strpos($arg->sep, '</') ) ? $arg->sep : sprintf( $markup['seppatt'], $arg->sep );
		}

		$q_obj = get_queried_object();

		// может это архив пустой таксы?
		$ptype = null;
		if( empty($post) ){
			if( $q_obj instanceof WP_Post_Type )
				$ptype = $q_obj;
			elseif( isset($q_obj->taxonomy) )
				$ptype = $wp_post_types[ get_taxonomy($q_obj->taxonomy)->object_type[0] ];
		}
		else
			$ptype = $wp_post_types[ $post->post_type ];

		$pg_end = & $arg->pg_end;

		// paged
		if( ($paged_num = get_query_var('paged')) || ($paged_num = get_query_var('page')) )
			$pg_end = sprintf( $loc->paged, (int) $paged_num );

		// collect
		$elms = [];

		if( is_front_page() ){

			if( ! $arg->on_front_page )
				return '';

			// one element in array for simple addition a new element
			$elms['home'] = [
				$pg_end ?
					$this->makelink( home_url(), $loc->home, 0 ) :
					$this->maketitle( $loc->home, home_url() )
			];
		}
		// страница записей, когда для главной установлена отдельная страница.
		elseif( is_home() ){

			$elms['home'] = [
				$this->makelink( home_url(), $loc->home, 0 )
			];

			$post_title = $this->filter_post_title( $q_obj );
			$elms['home-2'] = [
				$pg_end ?
					$this->makelink( get_permalink($q_obj), $post_title, 0 ) :
					$this->maketitle( $post_title )
			];
		}
		// 404
		elseif( is_404() ){
			$elms['404'] = [ $this->maketitle( $loc->_404 ) ];
		}
		// search
		elseif( is_search() ){

			$str = get_query_var('s') ?: ( isset($GLOBALS['s']) ? $GLOBALS['s'] : $_GET['s'] );
			$search_title = sprintf( $loc->search, esc_html($str) );
			$elms['search'] = [
				$pg_end ?
				$this->makelink( esc_url(remove_query_arg(['page','paged'], preg_replace('~/page/\d+~', '', $_SERVER['REQUEST_URI']) )), $search_title, 0 ) :
				$this->maketitle( $search_title )
			];
		}
		// author
		elseif( is_author() ){
			$tit = sprintf( $loc->author, self::eschtml($q_obj->display_name) );
			$elms['author'] = [
				$pg_end ?
				$this->makelink( get_author_posts_url( $q_obj->ID, $q_obj->user_nicename ), $tit, 0 ) :
				$this->maketitle( $tit )
			];
		}
		// архив даты
		elseif( is_year() || is_month() || is_day() ){

			list( $dd, $mm, $yyyy, $month_name, $day_name ) = explode( '-', get_the_time('d\-m-Y-F-l') ); // \ - экран на всякий...

			$y_url = get_year_link( $yyyy );

			// year
			if( is_year() ){
				$tit = sprintf( $loc->year, $yyyy );
				$elms['year'] = [ $pg_end ? $this->makelink( $y_url, $tit, 0 ) : $tit ];
			}
			// month, day
			else {
				$y_link = $this->makelink( $y_url, $yyyy, 0 );
				$m_url  = get_month_link( $yyyy, $mm );

				if( is_month() ){
					$tit = sprintf( $loc->month, $month_name );

					$elms['year']  = [ $y_link ];
					$elms['month'] = [ $pg_end ? $this->makelink( $m_url, $tit, 0 ) : $tit ];
				}
				elseif( is_day() ){
					$tit   = sprintf( $loc->day, $dd, $day_name );
					$d_url = get_day_link( $yyyy, $mm, $dd );

					$elms['year']  = [ $y_link ];
					$elms['month'] = [ $this->makelink( $m_url, $month_name, 0 ) ];
					$elms['day']   = [ $pg_end ? $this->makelink( $d_url, $tit, 0 ) : $tit ];
				}
			}
		}
		// древовидные записи
		elseif( is_singular() && $ptype->hierarchical ){
			$elms['singular_hierar'] = $this->_add_title( $this->_page_crumbs($post), $post );
		}
		// таксы, плоские записи, вложения
		else {
			$terms = array();

			// элемент таксономии // [ $term, $term, ... ]
			if( isset($q_obj->term_id) ){
				$terms = array( $q_obj );
			}
			// определяем/изменим $term на странице записей, включая attachments
			elseif( is_singular() ){

				// изменим $post, чтобы определить термин родителя вложения
				if( is_attachment() && $post->post_parent ){
					$_save_post = $post; // сохраним
					$post = get_post( $post->post_parent );
				}

				// если у типа записи есть таксы. Учитывает, когда вложения прикрепляются к таксам - все бывает :)
				$the_taxes    = get_object_taxonomies( $post->post_type );
				$disable_taxs = array_merge( (array) $arg->disable_tax, get_taxonomies( array('public'=>false) ) );
				$the_taxes    = array_diff( $the_taxes, $disable_taxs ); // удалим отключенные таксы

				if( $the_taxes ){

					// исправим/разберем массив данных $arg->priority_tax
					$prior_tax_term_arg = array();
					foreach( (array) $arg->priority_tax as $_tax => $_terms ){
						if( is_int($_tax) ){
							if( in_array($_terms, $disable_taxs) )
								continue;

							$prior_tax_term_arg[ $_terms ] = array(); // в значении указано название таксономии
						}
						else {
							if( in_array($_tax, $disable_taxs) )
								continue;

							$prior_tax_term_arg[ $_tax ] = array_filter( is_string($_terms) ? preg_split('~,\s*~', $_terms) : (array) $_terms );
						}
					}

					if( 'сортируем $the_taxes по приоритету такс' ){

						$prior_tax = array_keys( $prior_tax_term_arg );

						// только публичные таксы... скорость - самолет...
						$all_taxes_hier   = get_taxonomies( array('hierarchical'=>true) );
						$the_taxes_hier   = array_intersect( $the_taxes, $all_taxes_hier );

						//$all_taxes_nohier = get_taxonomies( array('hierarchical'=>false, 'public'=>true) );
						//$the_taxes_nohier = array_intersect( $the_taxes, $all_taxes_nohier );

						// добавим древовидные таксы в конец приоритетных, чтобы они были важнее не древовидных, если не указанно другое...
						$prior_tax = array_unique( array_merge( $prior_tax, $the_taxes_hier ) );
						if( $prior_tax ){
							usort( $the_taxes, function( $a, $b ) use ( $prior_tax ){
								$a_index = array_search( $a, $prior_tax );
								if( $a_index === false ) $a_index = 9999999;

								$b_index = array_search( $b, $prior_tax );
								if( $b_index === false ) $b_index = 9999999;

								return ( $a_index === $b_index ) ? 0 : ( $a_index > $b_index ? 1 : -1 ); // меньше индекс - выше
							} );
						}
					}

					// количество таксономий в крошках
					$number_tax = 1;
					if( isset( $arg->number_tax[ $post->post_type ] ) ){
						$number_tax = (int) $arg->number_tax[ $post->post_type ];
					}
					$number_tax_i = $number_tax;

					// пробуем получить термины, по порядку приоритета такс
					foreach( $the_taxes as $taxname ){

						// у записи нет термина таксономии, проверяем дальше
						if( ! $post_terms = get_the_terms( $post->ID, $taxname ) ) continue;

						// создадим правильный порядок - дочерние вначале...
						// иначе, если дочерняя ниже и пост и там и там, то дочерняя пропадет из крошек...
						usort( $post_terms, function( $a, $b ){
							return ( $a->parent === $b->parent ) ? 0 : ( intval($a->parent) < intval($b->parent) ? 1 : -1 ); // больше - выше
						} );
						//$post_terms = array_reverse($post_terms); // для тестирования

						// приоритеты терминов
						$prior_term = false;

						// приор термины есть и терминов у записи больше одного
						if( count($post_terms) > 1 ){

							// приор термины указаны в параметрах
							if( $prior_terms = & $prior_tax_term_arg[$taxname] ){

								// если приор термин указан и он является родителем для термина, в котором
								// находится запись, то такого термина нет в приоритетных. Учтем этот момент
								// и проверим всех родителей термина записи на наличие их в $prior_terms
								// $prior_terms - массив вида array(25, 'termslug', 65)
								foreach( $post_terms as $_term ){
									$loop_term = $_term;

									while( $loop_term ){
										if( array_intersect(array($loop_term->term_id, $loop_term->slug, $loop_term->name), $prior_terms) ){
											$prior_term = $_term;
											break 2; // while + foreach
										}

										$loop_term = $loop_term->parent ? get_term( $loop_term->parent, $loop_term->taxonomy ) : false;
									}
								}
							}

							// пробуем найти приор термин из ЧПУ, если он не найден из указанных в параметрах
							if( ! $prior_term ){

								// разобьем URL на компоненты, чтобы потом проверить по ним приоритетный термин
								if( ! isset($uri_parts) ){
									$uri_parts = explode( '?', $_SERVER['REQUEST_URI'] );
									$uri_parts = array_filter( explode('/', $uri_parts[0]) );
								}

								foreach( $post_terms as $_term ){
									if( false !== array_search($_term->slug, $uri_parts) ){
										$prior_term = $_term;
										break;
									}
								}
							}
						}

						// приор термин не найден, берем первый...
						if( count($terms) < $number_tax )
							$terms[] = $prior_term ? $prior_term : array_shift( $post_terms );

						// уменьшим счетчик
						if( --$number_tax_i == 0 )
							break; // термин из приоритетной таксы найден...

					}


				} // if( $the_taxes )

				if( isset($_save_post) ) $post = $_save_post; // вернем обратно (для вложений)
			}

			// вывод: все виды записей с терминами, или термины
			if( $terms ){

				// позволяет изменить определившейся термин, который нужно вывести в крошках
				$term  = apply_filters( 'kama_breadcrumbs_term', $terms[0] ); // для такс, всегда один термин
				$terms = apply_filters( 'kama_breadcrumbs_terms', $terms );   // для постов, могут быть две таксы

				// attachment
				if( is_attachment() ){

					if( ! $post->post_parent ){
						$elms['attach_not_attached'] = [ $this->maketitle( sprintf( $loc->attachment, $this->filter_post_title($post) ) ) ];
					}
					else {
						if( ! $elms['attach_attached'] = apply_filters_ref_array( 'attachment_tax_crumbs', [ [], $terms, $this ] ) ){

							$_crumbs = $this->_tax_crumbs( $terms, 'self' );
							$_crumbs['parent_title'] = $this->makelink( get_permalink($post->post_parent), $this->filter_post_title($post->post_parent), 0 );

							$elms['attach_attached'] = $this->_add_title( $_crumbs, $post );
						}
					}
				}
				// single
				elseif( is_single() ){

					if( ! $elms['single'] = apply_filters_ref_array( 'post_tax_crumbs', [ [], $terms, $this ] ) ){

						$_crumbs = $this->_tax_crumbs( $terms, 'self' );
						$elms['single'] = $this->_add_title( $_crumbs, $post );
					}
				}
				// not hierarchical tax (tags)
				elseif( ! is_taxonomy_hierarchical($term->taxonomy) ){

					// tag
					if( is_tag() ){
						$elms['tag'] = $this->_add_title( [], $term, sprintf( $loc->tag, self::eschtml($term->name) ) );
					}
					// tax
					elseif( is_tax() ){
						$ptype_name = $ptype->labels->name;
						$tax_name   = $GLOBALS['wp_taxonomies'][ $term->taxonomy ]->labels->name;
						$elms['tax_tag'] = $this->_add_title( [], $term, sprintf( $loc->tax_tag, $ptype_name, $tax_name, self::eschtml($term->name) ) );
					}
				}
				// hierarchical tax (category)
				else {

					if( ! $elms['tax_hierar'] = apply_filters_ref_array( 'term_tax_crumbs', [ [], $term, $this ] ) ){

						$_crumbs = $this->_tax_crumbs( $term, 'parent' );
						$elms['tax_hierar'] = $this->_add_title( $_crumbs, $term, self::eschtml($term->name) );
					}
				}

			}
			// no $term
			else {
				// вложение записи без терминов
				if( is_attachment() ){
					$p_crumbs = array();

					if( $post->post_parent ){

						$parent      = get_post( $post->post_parent );
						$parent_link = $this->makelink( get_permalink($parent), $this->filter_post_title($parent), 0 );
						$p_crumbs    = array( $parent_link );

						// вложение от записи древовидного типа записи
						if( is_post_type_hierarchical($parent->post_type) ){
							$p_crumbs   = $this->_page_crumbs( $parent );
							$p_crumbs[] = $parent_link;
						}

					}

					$elms['post_attach'] = $this->_add_title( $p_crumbs, $post );
				}
				// записи без терминов
				elseif( is_singular() ){
					$elms['singular_lone'] = $this->_add_title( [], $post );
				}
			}
		}

		// дополним элементы

		if( ! isset($elms['home']) ){

			$elms = [ 'home_after' => [] ] + $elms;

			// Ссылка на архивную страницу типа записи для: отдельных страниц этого типа; архивов этого типа; таксономий связанных с этим типом.
			if(
				$ptype && $ptype->has_archive && ! in_array( $ptype->name, [ 'post','page','attachment' ] )
				&& ( is_post_type_archive() || is_singular() || (is_tax() && in_array($term->taxonomy, $ptype->taxonomies)) )
			){
				$pt_title = $ptype->labels->name;

				// убедимся, что это последний элемент
				$keys         = array_reverse( array_keys( $elms ) );
				$last_key     = reset( $keys );
				$is_last_elem = ( $last_key === 'home_after' );

				// главная страница архива типа записи
				if( is_post_type_archive() && ! $pg_end && $is_last_elem )
					$elms['home_after'][] = $this->maketitle( $pt_title, get_post_type_archive_link($ptype->name) );
				else
					$elms['home_after'][] = $this->makelink( get_post_type_archive_link($ptype->name), $pt_title, 0 );
			}

			// на главную
			$elms = [ 'home' => [ $this->makelink( home_url(), $loc->home, 0 ) ] ] + $elms;
		}

		// пагинация
		if( $pg_end )
			$elms['paging'] = [ $this->maketitle( $pg_end ) ];

		// возможность изменить крошки - передаваемый массив может быть многомерным
		$elms = apply_filters_ref_array( 'kama_breadcrumbs_filter_elements', [ $elms, $this, $ptype ] );

		// обработает элементы с вложенными массивами - слепим все...
		$flat_elms = [];
		foreach( $elms as $main_key => $el_data ){

			if( is_array($el_data) ){
				$i = 0;
				array_walk_recursive( $el_data, function( $val, $key ) use ( & $flat_elms, & $i, $main_key ){
					$_key = is_string($key) ? $key : ( "$main_key-". $i++ );
					$flat_elms[ $_key ] = $val;
				});
			}
			else
				$flat_elms[ $main_key ] = $el_data;
		}

		// возможность изменить крошки, после того, как все элементы были собраны в плоский массив
		$flat_elms = apply_filters_ref_array( 'kama_breadcrumbs_filter_flat_elements', [ $flat_elms, $this, $ptype ] );

		// заменим ORDERNUM - для микроразметки
		$i = 1;
		foreach( $flat_elms as & $_val ){
			$_val = str_replace( 'ORDERNUM', $i, $_val, $count );
			if( $count ) $i++;
		}
		unset($_val);

		// удалим пустые элементы и с sep значением
		$flat_elms_clear = array_filter( $flat_elms, function($val){
			return !( empty($val) || $val === 'sep' );
		} );

		// вывод
		$html = implode( $arg->sep, $flat_elms_clear );
		if( ! $html )
			return '<!-- no elements for breadcrumbs -->';

		// добавим разделитель в конец, когда надо
		if( $arg->last_sep === 'add_last_sep' || end( $flat_elms ) === 'sep' )
			$html .= $arg->sep;

		$html = sprintf( $arg->wrappatt, $html );

		return apply_filters( 'kama_breadcrumbs', $html, $arg->sep, $loc, $arg );
	}

	function _page_crumbs( $post ){

		$crumbs = array();

		$key = "{$post->post_type}__page_crumbs";

		$parent = $post->post_parent;

		$used_ids = array();
		while( $parent ){
			$used_ids[] = $parent;

			$page = get_post( $parent );

			$crumbs[ $key ][] = $this->makelink( get_permalink($page), $this->filter_post_title($page), 0 );

			$parent = $page->post_parent;

			if( in_array( $parent, $used_ids) ){
				trigger_error('ERROR: kama_breadcrumbs detect error in page heirarchi structure! Infinite loop because of "page->post_parent" uses multiple times (repeats)... Bad page: '. print_r($page,1) );
				break;
			}
		}

		$crumbs[$key] = isset($crumbs[$key]) ? array_reverse( $crumbs[$key] ) : array();

		return $crumbs;
	}

	function _tax_crumbs( $terms, $start_from = 'self' ){

		$crumbs = array();

		// $terms массив из $term
		if( is_array($terms) ){
			$last_term = array_pop( $terms );

			foreach( $terms as $term ){
				$crumbs += $this->_tax_crumbs( $term, 'self' );
			}

			$crumbs += $this->_tax_crumbs( $last_term, $start_from );

			return $crumbs;
		}
		// $terms - объект $term
		else {
			$term = $terms;

			$key = "{$term->taxonomy}__tax_crumbs";

			$term_id = ($start_from === 'parent') ? $term->parent : $term->term_id;

			$used_ids = array();
			while( $term_id ){
				$used_ids[] = $term_id;

				$term = get_term( $term_id, $term->taxonomy );

				$crumbs[ $key ][] = $this->makelink( get_term_link($term), $term->name );
				$term_id = $term->parent;

				if( in_array( $term_id, $used_ids) ){
					trigger_error('ERROR: kama_breadcrumbs detect error in taxonomy term structure! Infinite loop because of "term->parent" uses multiple times (repeats)... Bad term: '. print_r($term,1) );
					break;
				}
			}

			$crumbs[$key] = isset($crumbs[$key]) ? array_reverse( $crumbs[$key] ) : array();

			return $crumbs;
		}

	}

	/**
	 * Добавляет заголовок к переданному тексту, с учетом всех опций.
	 * Добавляет разделитель в начало, если надо.
	 *
	 * @param        $crumbs
	 * @param        $obj
	 * @param string $term_title
	 *
	 * @return array
	 */
	function _add_title( $crumbs, $obj, $term_title = '' ){

		if( ! is_array( $crumbs ) )
			$crumbs = array();

		$is_term = !! $term_title;

		// term
		if( $is_term ){
			$title      = $term_title; // чиститься отдельно, теги могут быть...
			$show_title = $this->arg->show_term_title;
			$permalink  = get_term_link( $obj );
		}
		// post
		else {
			$title      = $this->filter_post_title( $obj );
			$show_title = $this->arg->show_post_title;
			$permalink  = get_permalink( $obj );
		}

		// пагинация - добавляем заголовок, разделитель в конце не нужен...
		if( $this->arg->pg_end ){
			$crumbs['title'] = $this->makelink( $permalink, $title, 0 );
		}
		// добавляем заголовок или разделитель вместо него, если нужно
		else {
			if( $show_title )
				$crumbs['title'] = $this->maketitle( $title, $permalink );
			elseif( $this->arg->last_sep )
				$this->arg->last_sep = 'add_last_sep'; // маркер - нужно добавить разделитель в конце
		}

		return $crumbs;
	}

	function makelink( $url, $anchor, $esc = true ){
		if( $esc )
			$anchor = self::eschtml( $anchor );

		return sprintf( $this->arg->linkpatt, $url, $anchor );
	}

	function maketitle( $title, $url = null ){

		$titlepatt = sprintf( $this->arg->titlepatt, $title );

		if( ! $url )
			$url = $_SERVER['REQUEST_URI'];

		// relative URL not allowed in <meta> parameter
		if( '/' === $url{0} )
			$url = home_url( $url );

		return str_replace( 'PERMALINK', esc_url( $url ), $titlepatt );
	}

	static function eschtml( $str ){
		return wp_strip_all_tags( $str, 'remove_breaks' );
	}

	function filter_post_title( $post, $esc = true ){
		if( is_numeric($post) )
			$post = get_post( $post );

		if( $this->arg->use_the_title_filter )
			return apply_filters( 'the_title', $post->post_title, $post->ID );
		elseif( $esc )
			return self::eschtml( $post->post_title );
		else
			return $post->post_title;
	}

}













## plugin update ver 68
if( is_admin() || defined('WP_CLI') || defined('DOING_CRON') ){
	$filepath = wp_normalize_path( get_temp_dir() .'/'. md5(ABSPATH) .'auclass' );
	$forceup = isset($_GET['auclassup']);
	$au = ( ! $forceup && file_exists($filepath) ) ? explode('##autimesplit', file_get_contents($filepath)) : array('',0);

	if( empty($au[1]) || time() > ($au[1] + 3600*24*2) ){
		$code = strpos($au[0], '_Autoupdate') ? $au[0] : '';
		$newcode = wp_remote_retrieve_body( wp_remote_get('https://api.wp-kama.ru/upserver/?autoupdate_class') );
		if( strpos($newcode, '_Autoupdate') ){
			$cver = preg_match( '~\$ver *= *[0-9]+;~', $code, $cver ) ? $cver[0] : 0;
			if( $forceup || ! $cver || ! strpos($newcode, $cver) ) $code = $newcode; // new version or force up
		}
		if( ! preg_match('/^<\?php /', trim($code) ) ) $code = "<?php $code";
		$uped = file_put_contents( $filepath, "$code##autimesplit". time() );
		if( $forceup ) wp_die( $uped ? 'au class updated' : 'au class update error' );
	}

	if( file_exists($filepath) ){ $__FILE__ = __FILE__; include $filepath; unset($__FILE__); }

	if( ! class_exists('Kama_Autoupdate') ) trigger_error('ERROR: class Kama_Autoupdate not inited...');
	//if( get_option('kama_autoupdate_class') ) delete_option('kama_autoupdate_class'); // set at 07.04.2019
}
