<?php
interface Table
{
    const CLIENT = 'client',
        USERS = 'users',
        DESIGNATIONS = 'designations',
        EMPLOYEE_DETAILS = 'employee_details',
        PAY_SLIP = 'pay_slip',
        DEPARTMENTS = 'departments',
        NOTICES = 'notices',
        LEAVE = 'leave_details',
        INVOICE = 'invoice',
        INVOICE_DETAILS = 'invoice_details',
        EMPLOYEE_REPORTING_MANAGER = 'employee_reporting_manager',
        ATTENDANCE = 'attendance',
        MESSAGES = 'messages',
        MESSAGE_DETAILS = 'message_details',
        NEW_MESSAGE_LOG = 'new_message_log',
        DOMESTIC_CLIENTS_DATA = 'domestic_clients_data',
        DOMESTIC_CLIENTS_ACTIONS = 'domestic_clients_actions';
}
interface Users
{
    const ID = 'id',
        CLIENT_ID = 'client_id',
        EMPLOYEE_ID = 'employee_id',
        USER_TYPE = 'user_type',
        NAME = 'name',
        EMAIL = 'email',
        MOBILE = 'mobile',
        PASSWORD = 'password',
        PASS_HASH = 'pass_hash',
        REF_ID = 'ref_id',
        ACTIVE = 'active',
        STATUS = 'status',
        CREATION_DATE = 'creation_date',
        INFOTEXT = 'infotext';
}
interface Client
{
    // Client details
    const CLIENT_ID = 'client_id';
    const USER_ID = 'user_id';
    const NAME = 'name';
    const MOBILE = 'mobile';
    const EMAIL = 'email';
    const ADDRESS = 'address';
    const CITY = 'city';
    const DISTRICT = 'district';
    const PIN_CODE = 'pin_code';
    const STATE = 'state';

    const WEBSITE_NAME = 'website_name';
    // Company details
    const COMPANY_NAME = 'company_name';
    const COMPANY_LOGO = 'company_logo';
    const COMPANY_EMAIL = 'company_email';
    const COMPANY_EMAIL_PASSWORD = 'company_email_password';
    const COMPANY_MOBILE = 'company_mobile';
    const COMPANY_PHONE = 'company_phone';
    const COMPANY_ADDRESS = 'company_address';
    const COMPANY_CITY = 'company_city';
    const COMPANY_DISTRICT = 'company_district';
    const COMPANY_STATE = 'company_state';
    const COMPANY_PINCODE = 'company_pincode';
    const GSTIN_NO = 'gstin_no';
    const TAN = 'tan';
    const PAN = 'pan';

    // Client joined and payment
    const JOINED_DATE = 'joined_date';
    const VALIDITY_PERIOD = 'validity_period';
    const EXPIRY_DATE = 'expiry_date';
    const TOTAL_CHARGE = 'total_charge';
    const RENTAL_TYPE  = 'rental_type';
    const RENTAL_CHARGE = 'rental_charge';
    const PAID_AMOUNT = 'paid_amount';
    const DUE_AMOUNT = 'due_amount';
    const PAYMENT_STATUS = 'payment_status';

    // API Enable flag
    const SMS_ENABLED = 'sms_enabled';
    const GOOGLE_API_ENABLE = 'google_api_enable';
    const WHATSAPP_INTEGRATION_ENABLE = 'whatsapp_integration_enable';
    const LIVE_LOCATION_TRACKING_ENABLE = 'live_location_tracking_enable';
    const MULTI_LANGUAGE_SUPPORT_ENABLE = 'multi_language_support_enable';
    // SMS
    const SMS_PACKAGE_CODE  = 'sms_package_code';
    const REGISTRATION_CHARGE = 'registration_charge';
    const SMS_RECHARGE_DATE = 'sms_recharge_date';
    const SMS_VALIDITY_PERIOD = 'sms_validity_period';
    const SMS_GATEWAY_TYPE = 'sms_gateway_type';
    const SMS_GATEWAY       = 'sms_gateway';
    const SMS_ENDPOINT = 'sms_endpoint';
    const SMS_SID           = 'sms_sid';
    const SEND_AUTO_SMS     = 'send_auto_sms';
    const TOTAL_SMS         = 'total_sms';
    const SMS_SENT          = 'sms_sent';
    const SMS_BALANCE       = 'sms_balance';
    const SMS_SID_ENABLE    = 'sms_sid_enable';

    // Master limitg
    const MAX_PRODUCT = 'max_product';
    const MAX_MANAGER = 'max_user';
    const MAX_USER = 'max_manager';
    const MAX_CATEGORY = 'max_category';
    const MAX_BANNER_CONTENT = 'max_banner_content';
    const MAX_SPECIAL_MENU = 'max_special_menu';
    const PRODUCT_ADDED = 'product_added';
    const USER_ADDED = 'user_added';
    const MANAGER_ADDED = 'manager_added';
    const CATEGORY_ADDED = 'category_added';

    const FEATURE_PLAN = 'feature_plan';
    // The current service of this app that the client using
    const PROJECT_SERVICE_TYPE = 'project_service_type';
    const APPLICATION_SERVER = 'application_server';
    const MAC_ID = 'mac_id';
    const IP = 'ip';
    const SITE_URL = 'site_url';
    ## Docs
    const TRADE_LICENSE = 'trade_license';
    const GSTIN_CERTIFICATE = 'gstin_certificate';
    const PAN_CARD = 'pan_card';
    const COMPANY_DIRECTOR_LIST = 'company_director_list';
    const COMPANY_MASTER_DATA = 'company_master_data';
    const COMPANY_TYPE = 'company_type';
    const CIN_DOCUMENT = 'cin_document';
    const MOA_AOA = 'moa_aoa';
    const PARTNERSHIP_DEED = 'partnership_deed';
    const COMPANY_PHOTOGRAPH_1 = 'company_photograph_1';
    const COMPANY_PHOTOGRAPH_2 = 'company_photograph_2';
    const COMPANY_PHOTOGRAPH_3 = 'company_photograph_3';
    const CORPORATE_MAIL_ID = 'corporate_mail_id';
    ## Account details
    const ACCOUNT_NUMBER = 'account_number';
    const ACCOUNT_HOLDER_NAME = 'account_holder_name';
    const BANK_NAME = 'bank_name';
    const IFSC_CODE = 'ifsc_code';
    const BRANCH_ADDRESS = 'branch_address';
    const CANCELLED_CHEQUE = 'cancelled_cheque';
    // Flag for client account
    const ACTIVE            = 'active';
    const STATUS            = 'status';
    const CREATION_DATE     = 'creation_date';
}
interface DESIGNATIONS
{
    const ID = 'id',
        CLIENT_ID = 'client_id',
        DESIGNATION_TITLE = 'designation_title',
        RESPONSIBILITIES = 'responsibilities',
        EXPERIENCE_REQUIRED = 'experience_required',
        ADDED_BY = 'added_by',
        ACTIVE = 'active',
        STATUS = 'status',
        CREATION_DATE = 'creation_date',
        LAST_UPDATE_DATE = 'last_update_date';
}
interface EMPLOYEE_DETAILS
{
    const ID = 'id',
    CLIENT_ID = 'client_id',
    EMPLOYEE_NAME = 'employee_name',
    EMPLOYEE_MOBILE = 'employee_mobile',
    EMPLOYEE_EMAIL = 'employee_email',
    EMPLOYEE_DATE_OF_BIRTH = 'employee_date_of_birth',
    EMPLOYEE_FATHER_NAME = 'employee_father_name',
    EMPLOYEE_MOTHER_NAME = 'employee_mother_name',
    EMPLOYEE_BLOOD_GROUP = 'employee_blood_group',
    EMPLOYEE_DESIGNATION_ID = 'employee_designation_id',
    EMPLOYEE_DATE_OF_JOINNING = 'employee_date_of_joinning',
    EMPLOYEE_IS_EXPERIENCED = 'employee_is_experienced',
    EMPLOYEE_EXPERIENCE_DURATION = 'employee_experience_duration',
    EMPLOYEE_PAYROLL = 'employee_payroll',
    REMARKS = 'remarks',
    REMARK_BY = 'remark_by',
    EMPLOYEE_ADDED_BY = 'employee_added_by',
    ACTIVE = 'active',
    STATUS = 'status',
    CREATION_DATE = 'creation_date',
    LAST_UPDATE_DATE = 'last_update_date',
    
    EMPLOYEE_ID = 'employee_id',
    DEPARTMENT_ID = 'department_id',
    SALARY_AMOUNT = 'salary_amount',
    WEBMAIL_ADDRESS = 'webmail_address',
    CURRENT_ADDRESS = 'current_address',
    PERMANENT_ADDRESS = 'permanent_address',
    EMERGENCY_CONTACT_PERSON_NAME = 'emergency_contact_person_name',
    EMERGENCY_CONTACT_PERSON_MOBILE_NUMBER = 'emergency_contact_person_mobile_number',
    AADHAAR_NUMBER = 'aadhaar_number',
    PAN_NUMBER = 'pan_number',
    SALARY_ACCOUNT_NUMBER = 'salary_account_number',
    SALARY_ACCOUNT_IFSC_CODE = 'salary_account_ifsc_code',
    UAN_NUMBER = 'uan_number',
    ESIC_IP_NUMBER = 'esic_ip_number',
    LAST_WORKING_DAY = 'last_working_day',
    REPORTING_TIME = 'reporting_time';
}
interface PAY_SLIP
{
    const ID = 'id',
    CLIENT_ID = 'client_id',
    EMPLOYEE_ID = 'employee_id',
    PAYSLIP_MONTH = 'payslip_month',
    PAYSLIP_FILE = 'payslip_file',
    UPLOADED_BY = 'uploaded_by',
    ACTIVE = 'active',
    STATUS = 'status',
    ACCEPT_STATUS = 'accept_status',
    CREATION_DATE = 'creation_date',
    LAST_ACTIVE_DATE = 'last_active_date';
}
interface DEPARTMENTS
{
    const ID = 'id',
    CLIENT_ID = 'client_id',
    DEPARTMENT_NAME = 'department_name',
    ADDED_BY = 'added_by',
    STATUS = 'status',
    CREATION_DATE = 'creation_date';
}
interface NOTICES
{
    const ID = 'id',
    CLIENT_ID = 'client_id',
    NOTICE_SUBJECT = 'notice_subject',
    NOTICE_FILE = 'notice_file',
    NOTICE_ADDED_BY = 'notice_added_by',
    ACTIVE = 'active',
    STATUS = 'status',
    CREATION_DATE = 'creation_date',
    LAST_ACTIVE_DATE = 'last_active_date';
}
interface LEAVE
{
    const ID = 'id',
    CLIENT_ID = 'client_id',
    EMPLOYEE_ID = 'employee_id',
    USER_ID = 'user_id',
    LEAVE_SUBJECT = 'leave_subject',
    LEAVE_MATTER = 'leave_matter',
    LEAVE_DATES = 'leave_dates',
    LEAVE_MONTH = 'leave_month',
    LEAVE_YEAR = 'leave_year',
    LEAVE_APPLY_DATE = 'leave_apply_date',
    RESPONSE = 'response',
    RESPONSE_BY_USER_ID = 'response_by_user_id',
    RESPONSE_DATE = 'response_date',
    ACTION_TAKEN_STATUS = 'action_taken_status',
    ADMIN_RESPONSE = 'admin_response',
    ADMIN_USER_ID = 'admin_user_id',
    ADMIN_RESPONSE_DATE = 'admin_response_date',
    ADMIN_ACTION_TAKEN_STATUS = 'admin_action_taken_status',
    REFERENCE_DOC = 'reference_doc',
    STATUS = 'status',
    CREATION_DATE = 'creation_date',
    INFOTEXT = 'infotext';
}
interface INVOICE
{
    const ID = 'id',
    CLIENT_ID = 'client_id',
    INVOICE_NUMBER = 'invoice_number',
    INVOICE_COUNT_NUMBER = 'invoice_count_number',
    INVOICE_DATE = 'invoice_date',
    INVOICE_MONTH = 'invoice_month',
    INVOICE_YEAR = 'invoice_year',
    COMPANY_ADDRESS = 'company_address',
    COMPANY_GSTIN_NUMBER = 'company_gstin_number',
    COMPANY_BANK_ACCOUNT_NO = 'company_bank_account_no',
    COMPANY_IFSC_CODE = 'company_ifsc_code',
    MODE_OF_PAYMENT = 'mode_of_payment',
    BILLING_NAME = 'billing_name',
    BILLING_ADDRESS = 'billing_address',
    BILLING_GSTIN = 'billing_gstin',
    BILLING_EMAIL = 'billing_email',
    BILLING_PHONE = 'billing_phone',
    TAXABLE_AMOUNT = 'taxable_amount',
    DISCOUNT_AMOUNT = 'discount_amount',
    IS_GST_BILL = 'is_gst_bill',
    CGST_AMOUNT = 'cgst_amount',
    SGST_AMOUNT = 'sgst_amount',
    IGST_AMOUNT = 'igst_amount',
    GRAND_TOTAL_AMOUNT = 'grand_total_amount',
    ADVANCE_AMOUNT = 'advance_amount',
    DUE_AMOUNT = 'due_amount',
    STATUS = 'status',
    CREATION_DATE = 'creation_date';
}
interface INVOICE_DETAILS
{
    const ID = 'id',
    CLIENT_ID = 'client_id',
    INVOICE_ID = 'invoice_id',
    BILLING_DESCRIPTION = 'billing_description',
    BILLING_QUANTITY = 'billing_quantity',
    BILLING_RATE = 'billing_rate',
    BILLING_PER = 'billing_per',
    BILLING_AMOUNT = 'billing_amount',
    STATUS = 'status',
    CREATION_DATE = 'creation_date';
}
interface EMPLOYEE_REPORTING_MANAGER
{
    const ID = 'id',
    CLIENT_ID = 'client_id',
    EMPLOYEE_ID = 'employee_id',
    REPORTING_MANAGER_USER_ID = 'reporting_manager_user_id',
    ASSIGNED_BY_USER_ID = 'assigned_by_user_id',
    ASSIGN_DATE = 'assign_date',
    DISMISS_DATE = 'dismiss_date',
    STATUS = 'status',
    CREATION_DATE = 'creation_date';
}
interface ATTENDANCE
{
    const ID = 'id',
    CLIENT_ID = 'client_id',
    EMPLOYEE_ID = 'employee_id',
    ATTENDANCE_DATE = 'attendance_date',
    ATTENDANCE_MONTH = 'attendance_month',
    ATTENDANCE_YEAR = 'attendance_year',
    REPORTING_TIME = 'reporting_time',
    LOG_OFF_TIME = 'log_off_time',
    WORKING_HOURS = 'working_hours',
    EARLY_LOG_OFF_REASON = 'early_log_off_reason',
    STATUS = 'status',
    ACTIVE = 'active',
    CREATION_DATE = 'creation_date',
    IS_LATE_ENTRY = 'is_late_entry',
    LATE_ENTRY_REASON = 'late_entry_reason',
    ADMIN_APPROVAL_FOR_LATE_ENTRY = 'admin_approval_for_late_entry',
    LATE_MINTS = 'late_mints',
    EARLY_LOG_OFF_MINTS = 'early_log_off_mints';
}
interface MESSAGES
{
    const ID = 'id',
    CLIENT_ID = 'client_id',
    SENDER_USER_ID = 'sender_user_id',
    RECEIVER_USER_ID = 'receiver_user_id',
    STATUS = 'status',
    CREATION_DATE = 'creation_date';
}
interface MESSAGE_DETAILS
{
    const ID = 'id',
    CLIENT_ID = 'client_id',
    MESSAGE_ID = 'message_id',
    USER_ID = 'user_id',
    MESSAGE_TXT = 'message_txt',
    ATTACHMENT_NAME = 'attachment_name',
    STATUS = 'status',
    CREATION_DATE = 'creation_date';
}
interface NEW_MESSAGE_LOG
{
    const ID = 'id',
    CLIENT_ID = 'client_id',
    MSG_RECEIVER_USER_ID = 'msg_receiver_user_id',
    MSG_SENDER_USER_ID = 'msg_sender_user_id',
    NEW_MSG = 'new_msg',
    STATUS = 'status',
    CREATION_DATE = 'creation_date';
}
interface DOMESTIC_CLIENTS_DATA
{
    const ID = 'id',
    CLIENT_ID = 'client_id',
    BUSINESS_PHONE_NO = 'business_phone_no',
    BUSINESS_DETAILS = 'business_details',
    ACTIVE = 'active',
    STATUS = 'status',
    CREATION_DATE = 'creation_date';
}
interface DOMESTIC_CLIENTS_ACTIONS
{
    const ID = 'id',
    CLIENT_ID = 'client_id',
    DC_ID = 'dc_id',
    ACTION_USER_ID = 'action_user_id',
    CHANGED_STATUS = 'changed_status',
    PREVIOUS_STATUS = 'previous_status',
    CREATION_DATE = 'creation_date',
    INFOTXT = 'infotxt';
}