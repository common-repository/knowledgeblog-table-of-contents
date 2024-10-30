<?php
  /*
   Plugin Name: Knowledgeblog Table of Contents
   Plugin URI: http://knowledgeblog.org/knowledgeblog-table-of-contents-plugin/
   Description: Display alphabetic list of articles in a particular category
   Version: 0.4
   Author: Simon Cockell
   Author URI: http://knowledgeblog.org
   License: GPL2

   Copyright 2010-11. Simon Cockell (s.j.cockell@newcastle.ac.uk)
   Newcastle University. 
  
   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License, version 2, as 
   published by the Free Software Foundation.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  */


class KToC{

  function init(){
    register_activation_hook(__FILE__, array(__CLASS__, 'ktoc_install'));
    
    add_shortcode('ktoc',
                  array(__CLASS__, 'ktoc_shortcode' ));
    
    add_action('admin_menu', array(__CLASS__, 'ktoc_menu'));

    add_filter('plugin_action_links', array(__CLASS__, 'ktoc_settings_link'), 9, 2 );
  }

  function ktoc_install() {
    //registers default options
    add_option('display_category', 'All');
  }
  
  function debug(){
    echo "A debug statement";
  }

  function ktoc_shortcode($atts,$content){
    extract(shortcode_atts(array(
                'cat' => get_option('display_category'),
                'fill' => 'by',
            ), $atts));
	$categories = get_categories();
	$items = array();
	foreach ($categories as $category) {
		$name = $category->cat_name;
		if ($name == $cat) {
			$catposts = get_posts('numberposts=-1&order=ASC&orderby=title&category_name='.$category->cat_name);
			foreach ($catposts as $post) {
				if(!function_exists('coauthors')) {
					$author = get_userdata($post->post_author);
					$author_name = $author->user_login;
					$author_realname = $author->first_name." ".$author->last_name;
					$author_url = $author->user_url;
					$item = "<li><a href=" . get_permalink($post->ID) . ">" . get_the_title($post->ID) . "</a> $fill ";
					if ($author_realname != ' ') {
						$item .= $author_realname ."</li>\n";
					}
					else {
						$item .= $author_name ."</li>\n";
					}
					$items[] = $item;
				}
				else {
                    //deal with co-authors
                    $item = "<li><a href=" . get_permalink($post->ID) . ">" . get_the_title($post->ID) . "</a> $fill ";
                    $authors = get_coauthors($post->ID);
                    $i = 1;
                    $len = count($authors);
                    $author_html = '';
                    foreach ($authors as $author) {
				        $author_name = $author->user_login;
				        $author_realname = $author->first_name." ".$author->last_name;
				        $author_url = $author->user_url;
				        if ($author_realname != ' ') {
					        $author_html .= $author_realname;
				        }
				        else {
					        $author_html .= $author_name;
				        }
                        if ($i == $len-1) {
                            $author_html .= " and ";
                        }
                        elseif ($i < $len-1) {
                            $author_html .= ", ";
                        }
                        else {
                            $author_html .= "</li>";
                        }
                        $i++;
                    }
					$item .= $author_html;
					$items[] = $item;
                }
			}
		}
		$list = "<ul>\n";
		foreach ($items as $item) {
			$list .= $item."\n";
		}
		$list .= "</ul>\n";
	}
		return $list;
  }

  //add a link to settings on the plugin management page
  function ktoc_settings_link( $links, $file ) {
    if ($file == 'knowledgeblog-table-of-contents/kblog-table-of-contents.php' && function_exists('admin_url')) {
        $settings_link = '<a href="' .admin_url('options-general.php?page=kblog-table-of-contents.php').'">'. __('Settings') . '</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
  }

  function ktoc_menu() {
    add_options_page('Knowledgeblog-ToC Plugin Options', 'Knowledgeblog Table of Contents Plugin', 'manage_options', 'kblog-table-of-contents', array(__CLASS__, 'ktoc_plugin_options'));
  }

  function ktoc_plugin_options() {
      if (!current_user_can('manage_options'))  {
        wp_die( __('You do not have sufficient permissions to access this page.') );
      }
      echo '<div class="wrap" id="ktoc-options">
<h2>Kblog Table of Contents Plugin Options</h2>
';
    if ($_POST['ktoc_hidden'] == 'Y') {
        //process form
        update_option('display_category', $_POST['display_category']);
        echo '<p><i>Options updated</i></p>';
    }
?>   
      <form id="ktoc" name="ktoc" action="" method='POST'>
      <input type="hidden" name="ktoc_hidden" value="Y">
      <table class="form-table">
      <tr valign="middle">
      <th scope="row">Select Category to Display<br/>
	  <font size="-2">Which category do you want displayed in your Table of Contents?</font></th>
      <td><select name="display_category"><?php 
		$categories = get_categories();
		$selected = get_option('display_category');
		foreach ($categories as $cat) {
			$name = $cat->cat_name;
				echo "<option value='$name'";
			if ($name == $selected) {
				echo "SELECTED";
			}
				echo ">$name</option>\n";
		}
      ?>
	  </select>
	  </td>
      </tr>
      </table>
      <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
      </p>
      </form>
      </div>
<?php
  }

}

KToC::init();

/*
 function mathjax_latex_hooks_footer()
 {
 $blogsurl = get_bloginfo('wpurl') . '/wp-content/plugins/' 
 . basename(dirname(__FILE__));
 echo '<script type="text/javascript" src="' . $blogsurl . '/MathJax/MathJax.js"></script>';

 }
*/




?>
