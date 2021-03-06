<?php
//
//    ______         ______           __         __         ______
//   /\  ___\       /\  ___\         /\_\       /\_\       /\  __ \
//   \/\  __\       \/\ \____        \/\_\      \/\_\      \/\ \_\ \
//    \/\_____\      \/\_____\     /\_\/\_\      \/\_\      \/\_\ \_\
//     \/_____/       \/_____/     \/__\/_/       \/_/       \/_/ /_/
//
//   上海商创网络科技有限公司
//
//  ---------------------------------------------------------------------------------
//
//   一、协议的许可和权利
//
//    1. 您可以在完全遵守本协议的基础上，将本软件应用于商业用途；
//    2. 您可以在协议规定的约束和限制范围内修改本产品源代码或界面风格以适应您的要求；
//    3. 您拥有使用本产品中的全部内容资料、商品信息及其他信息的所有权，并独立承担与其内容相关的
//       法律义务；
//    4. 获得商业授权之后，您可以将本软件应用于商业用途，自授权时刻起，在技术支持期限内拥有通过
//       指定的方式获得指定范围内的技术支持服务；
//
//   二、协议的约束和限制
//
//    1. 未获商业授权之前，禁止将本软件用于商业用途（包括但不限于企业法人经营的产品、经营性产品
//       以及以盈利为目的或实现盈利产品）；
//    2. 未获商业授权之前，禁止在本产品的整体或在任何部分基础上发展任何派生版本、修改版本或第三
//       方版本用于重新开发；
//    3. 如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回并承担相应法律责任；
//
//   三、有限担保和免责声明
//
//    1. 本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的；
//    2. 用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未获得商业授权之前，我们不承
//       诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任；
//    3. 上海商创网络科技有限公司不对使用本产品构建的商城中的内容信息承担责任，但在不侵犯用户隐
//       私信息的前提下，保留以任何方式获取用户信息及商品信息的权利；
//
//   有关本产品最终用户授权协议、商业授权与技术服务的详细内容，均由上海商创网络科技有限公司独家
//   提供。上海商创网络科技有限公司拥有在不事先通知的情况下，修改授权协议的权力，修改后的协议对
//   改变之日起的新授权用户生效。电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和
//   等同的法律效力。您一旦开始修改、安装或使用本产品，即被视为完全理解并接受本协议的各项条款，
//   在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本
//   授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。
//
//  ---------------------------------------------------------------------------------
//
defined('IN_ECJIA') or exit('No permission resources.');

/**
 * ECJIA消息模块
 */
class admin_config extends ecjia_admin {

	private $db_mobile_manage;
	
	public function __construct() {
		parent::__construct();
	
		$this->db_mobile_manage = RC_Model::model('mobile/mobile_manage_model');
		
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('smoke');
		
		RC_Style::enqueue_style('chosen');
		RC_Style::enqueue_style('uniform-aristo');
		RC_Script::enqueue_script('jquery-uniform');
		RC_Script::enqueue_script('jquery-chosen');
		RC_Script::enqueue_script('push_config', RC_App::apps_url('statics/js/push_config.js', __FILE__), array(), false, true);
		
		RC_Script::localize_script('push_config', 'js_lang', RC_Lang::get('push::push.js_lang'));
		
		RC_Style::enqueue_style('push_event', RC_App::apps_url('statics/css/push_event.css', __FILE__), array(), false, false);
	}

	/**
	 * 消息配置页面
	 */
	public function init () {
	    $this->admin_priv('push_config_manage');
	   
		$this->assign('ur_here', RC_Lang::get('push::push.msg_config'));
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('push::push.msg_config')));

    	$this->assign('config_appname',       ecjia::config('app_name'));//应用名称
    	$this->assign('config_apppush',       ecjia::config('app_push_development'));
    	
    	$this->assign('config_pushplace',     ecjia::config('push_order_placed'));//客户下单
    	$this->assign('config_pushpay',       ecjia::config('push_order_payed'));//客户付款
    	$this->assign('config_pushship',      ecjia::config('push_order_shipped'));//商家发货
    	$this->assign('config_pushsignin',    ecjia::config('push_user_signin'));//用户注册
    	
    	$mobile_manage = $this->db_mobile_manage->select();
    	
    	$push_event = RC_Model::model('push/push_event_model')->field(array('event_name', 'event_code'))->group('event_code')->where(array('is_open' => 1))->select();
    	
    	/* 客户下单*/
    	$push_order_placed_apps = ecjia::config('push_order_placed_apps');
    	
    	/* 客户付款*/
    	$push_order_payed_apps = ecjia::config('push_order_payed_apps');
    	 
    	/* 商家发货*/
    	$push_order_shipped_apps = ecjia::config('push_order_shipped_apps');
    	
    	/*配送员消息推送*/
    	$push_express_assign	= ecjia::config('push_express_assign');
    	$push_express_grab		= ecjia::config('push_express_grab');
    	$push_express_assign_event	= ecjia::config('push_express_assign_event');
    	$push_express_grab_event	= ecjia::config('push_express_grab_event');
    	
    	$this->assign('push_express_assign',	$push_express_assign);
    	$this->assign('push_express_grab',		$push_express_grab);
    	$this->assign('push_express_assign_event',	$push_express_assign_event);
    	$this->assign('push_express_grab_event', 	$push_express_grab_event);
    	
    	$this->assign('mobile_manage',     	$mobile_manage);
    	$this->assign('apps_group_order',	$push_order_placed_apps);
    	$this->assign('apps_group_payed',   $push_order_payed_apps);
    	$this->assign('apps_group_shipped', $push_order_shipped_apps);
    	
    	$this->assign('push_event' , $push_event);
    	$this->assign('current_code', 'push');
		$this->assign('form_action', RC_Uri::url('push/admin_config/update'));
		
		$this->display('push_config.dwt');
	}
		
	/**
	 * 处理消息配置
	 */
	public function update() {
		$this->admin_priv('push_config_manage', ecjia::MSGTYPE_JSON);
		
		ecjia_config::instance()->write_config('app_name',             $_POST['app_name']);
		ecjia_config::instance()->write_config('app_push_development', $_POST['app_push_development']);
		
		ecjia_config::instance()->write_config('push_order_placed',    intval($_POST['config_order']));
		ecjia_config::instance()->write_config('push_order_payed',     intval($_POST['config_money']));
		ecjia_config::instance()->write_config('push_order_shipped',   intval($_POST['config_shipping']));
		ecjia_config::instance()->write_config('push_user_signin',     intval($_POST['config_user']));
		
		/*配送员消息推送*/
		ecjia_config::instance()->write_config('push_express_assign',  intval($_POST['push_express_assign']));
		ecjia_config::instance()->write_config('push_express_grab',    intval($_POST['push_express_grab']));
		
		$push_express_assign_event = trim($_POST['push_express_assign_event']);
		ecjia_config::instance()->write_config('push_express_assign_event', $push_express_assign_event);
		$push_express_grab_event = trim($_POST['push_express_grab_event']);
		ecjia_config::instance()->write_config('push_express_grab_event', $push_express_grab_event);
		$push_order_placed_apps = trim($_POST['push_order_placed_apps']);
		ecjia_config::instance()->write_config('push_order_placed_apps', $push_order_placed_apps);
		
		$push_order_payed_apps = trim($_POST['push_order_payed_apps']);
		ecjia_config::instance()->write_config('push_order_payed_apps', $push_order_payed_apps);
		
		$push_order_shipped_apps = trim($_POST['push_order_shipped_apps']);
		ecjia_config::instance()->write_config('push_order_shipped_apps', $push_order_shipped_apps);
		
		ecjia_admin::admin_log('推送消息>消息配置', 'setup', 'config');
		return $this->showmessage(RC_Lang::get('push::push.update_config_success'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('push/admin_config/init')));
	}
}

//end