<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	
$lang = array(
	'lang_telegram_adjust_in' => "手動存入",
	'lang_telegram_adjust_out' => "手動提取",
	'lang_telegram_adjust_deposit' => "入",
	'lang_telegram_adjust_withdrawal' => "洗",
	'lang_telegram_register_time' => "註冊時間",
	'lang_telegram_admin_register' => "創建",
	'lang_telegram_member_register' => "會員創建",
	'lang_telegram_platform_new_member' => "Bet98新會員",
	'lang_telegram_feedback_customer_service' => "客服服務",
	'lang_telegram_feedback_deposit_withdrawal' => "出款效率",
	'lang_telegram_feedback_account' => "帳號問題",
	'lang_telegram_feedback_error_message' => "網站介面錯誤回報",
	'lang_telegram_feedback_others' => "其他",
	'lang_telegram_register_domain' => "註冊網址",
	'lang_telegram_register_platform_name' => "網站名稱",
	'lang_telegram_register_account' => "會員帳號",
	'lang_telegram_register_platform' => "Bet98",
	'lang_telegram_register_admin' => "後台創建",
	'lang_telegram_logs_admin' => "操作人員",
	'lang_telegram_logs_platform' => "操作平台",
	'lang_telegram_logs_menu' => "操作項目",
	'lang_telegram_logs_content' => "操作內容",
	'lang_telegram_logs_create_user_account' => "創建代理帳號",
	'lang_telegram_logs_create_sub_account' => "創建後台帳號",
	'lang_telegram_logs_update_user_account_character' => "修改代理帳號角色",
	'lang_telegram_logs_update_sub_account_character' => "修改後台帳號角色",
	'lang_telegram_logs_update_character_permission' => "修改角色權限",
	'lang_telegram_logs_player_list_export' => "導出會員資料",
	'lang_telegram_logs_user_role' => "角色",
	'lang_telegram_logs_create_account' => "創建帳號",
	'lang_telegram_logs_user_sub_domain' => "代理域名",
	'lang_telegram_logs_user_possess' => "佔成",
	'lang_telegram_logs_account' => "帳號",
	'lang_telegram_logs_player_export' => "導出會員",
	'lang_telegram_logs_all' => "全部",
	'lang_telegram_logs_from' => "由",
	'lang_telegram_logs_to' => "至",
	'lang_telegram_logs_empty' => "未設置",
	'lang_telegram_permission_player_report_export_excel' => "會員資料导出",
	'lang_telegram_permission_deposit_report_export_excel' => "存款資料导出",
	'lang_telegram_permission_withdrawal_report_export_excel' => "提款資料导出",
	'lang_telegram_permission_win_loss_player_report_export_excel' => "輸贏報表(會員)資料导出",
	'lang_telegram_permission_yearly_report_export_excel' => "年度報告資料导出",
	'lang_telegram_permission_transaction_report_export_excel' => "投注記錄資料导出",
	'lang_telegram_permission_point_report_export_excel' => "點數出入記錄資料导出",
	'lang_telegram_permission_cash_report_export_excel' => "額度記錄資料导出",
	'lang_telegram_permission_reward_report_export_excel' => "獎勵記錄資料导出",
	'lang_telegram_permission_verify_report_export_excel' => "驗證碼紀錄資料导出",
	'lang_telegram_permission_wallet_report_export_excel' => "錢包記錄資料导出",
	'lang_telegram_permission_player_risk_report_export_excel' => "會員風控資料导出",
	'lang_telegram_permission_player_login_report_export_excel' => "會員登入記錄資料导出",
	'lang_telegram_permission_player_list_export_excel' => "會員資料导出",
	'lang_telegram_permission_player_promotion_list_export_excel' => "會員優惠資料导出",
	'lang_telegram_permission_player_mobile' => "會員手機",
	'lang_telegram_permission_player_line_id' => "會員Line",
	'lang_telegram_permission_player_account_name' => "帳戶名稱",
	'lang_telegram_permission_character_off' => "關閉",
	'lang_telegram_permission_character_on' => "開啟",
	'lang_telegram_permission_win_loss_export_excel' => "輸贏報表(代理)資料導出",
	'lang_telegram_permission_withdrawal_verify_report_export_excel' => "提款驗證總表資料導出",
	'lang_telegram_permission_register_deposit_rate_report_export_excel' => "註冊儲值率報告資料導出",
	'lang_telegram_permission_register_deposit_rate_yearly_report_export_excel' => "註冊儲值率年度報告資料導出",
	'lang_telegram_logs_player_agent_list_export' => "導出代理會員資料",
	'lang_telegram_risk_deposit_online' => "ATM",
	'lang_telegram_risk_deposit_hypermarket' => "超商",
	'lang_telegram_risk_deposit_credit_card' => "信用卡",
	'lang_telegram_risk_deposit_offline' => "約定轉帳",
	'lang_telegram_risk_deposit_adjust' => "額度調整",
	'lang_telegram_risk_total' => "合計",
	'lang_telegram_risk_today_deposit' => "本日存款",
	'lang_telegram_risk_today_month' => "本月存款",
	'lang_telegram_risk_today_winloss' => "本日輸贏",
	'lang_telegram_risk_month_winloss' => "本月輸贏",
	'lang_telegram_risk_year_winloss' => "年度輸贏",
	'lang_telegram_risk_reach_winloss' =>"已達峰頂",
	'lang_telegram_risk_reach_winloss_percent' =>"已達峰頂%",
	'lang_telegram_risk_slot_title' => "電子盈利大於5萬以上",
	'lang_telegram_risk_sportbook_title' => "體育今日盈利大於5萬",
	'lang_telegram_risk_casino_title' => "營利大於3萬",
	'lang_telegram_risk_casino_type_baccarat' => "百家乐",
	'lang_telegram_risk_casino_type_dragon_tiger' => "龙虎",
	'lang_telegram_risk_casino_type_roulette' => "轮盘",
	'lang_telegram_risk_casino_type_sicbo' => "骰寶",
	'lang_telegram_risk_casino_type_fan_tan' => "番攤",
	'lang_telegram_risk_casino_type_bull_bull' => "牛牛",
	'lang_telegram_risk_casino_type_poker' => "撲克牌",
	'lang_telegram_risk_casino_type_three_card_poker' => "三张牌扑克",
	'lang_telegram_risk_casino_type_se_die' => "色碟",
	'lang_telegram_risk_casino_type_fish_prawn_crab_dice' => "鱼虾蟹",
	'lang_telegram_risk_casino_type_sam_gong' => "三公",
	'lang_telegram_risk_casino_type_live_lucky_5' => "Live Lucky 5",
	'lang_telegram_risk_casino_type_live_lucky_10' => "Live Lucky 10",
	'lang_telegram_risk_casino_type_pok_deng ' => "博丁",
	'lang_telegram_risk_casino_type_rock_paper_scissors' => "石頭剪刀布",
	'lang_telegram_risk_casino_type_win_three_cards' => "三張紙牌",
	'lang_telegram_risk_casino_type_mini_game' => "Mini Game",
	'lang_telegram_risk_casino_type_multiplayer_game' => "Multiplayer Game",
	'lang_telegram_risk_casino_type_money_wheel' => "Money Wheel",
	'lang_telegram_risk_casino_type_tip' => "小费",
	'lang_telegram_risk_casino_type_zha_jin_hua' => "炸金花",
	'lang_telegram_risk_casino_type_wenzhou_pai_gow' => "温州牌狗",
	'lang_telegram_risk_casino_type_mahjong_tiles' => "麻将牌",
	'lang_telegram_risk_casino_type_andar_bahar' => "安达巴哈尔",
	'lang_telegram_risk_casino_type_others' => "其他",
	'lang_telegram_risk_pending_withdrawal' => "申請提領",
	'lang_telegram_risk_game_balance' => "錢包餘額",
	'lang_telegram_risk_deposit_point' => "信用點數",
	'lang_telegram_risk_upline' => "代理",
	'lang_telegram_risk_member' => "會員",
	'lang_telegram_risk_withdrawal_title' => "提款大於30萬以上",
	'lang_telegram_risk_promotion_rebate_title' => "返水優惠大於60萬以上",
	'lang_telegram_risk_pending_promotion_rebate' => "返水優惠",
);