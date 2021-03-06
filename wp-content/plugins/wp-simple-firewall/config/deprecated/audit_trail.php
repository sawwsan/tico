{
  "slug":             "audit_trail",
  "properties":       {
    "slug":                  "audit_trail",
    "name":                  "Audit Trail",
    "sidebar_name":          "Audit Trail",
    "show_module_menu_item": false,
    "show_module_options":   true,
    "storage_key":           "audit_trail",
    "tagline":               "Track All Site Activity: Who, What, When and Where",
    "show_central":          true,
    "access_restricted":     true,
    "premium":               false,
    "run_if_whitelisted":    true,
    "run_if_verified_bot":   false,
    "run_if_wpcli":          true,
    "order":                 110
  },
  "menu_items":       [
    {
      "title": "Audit Trail",
      "slug":  "audit-redirect"
    }
  ],
  "custom_redirects": [
    {
      "source_mod_page": "audit-redirect",
      "target_mod_page": "insights",
      "query_args":      {
        "inav": "audit_trail"
      }
    }
  ],
  "admin_notices":    {
    "new-audit-trail": {
      "id":               "new-audit-trail",
      "schedule":         "conditions",
      "valid_admin":      true,
      "plugin_page_only": true,
      "can_dismiss":      true,
      "type":             "info"
    }
  },
  "sections":         [
    {
      "slug":        "section_localdb",
      "primary":     true,
      "title":       "Log To DB",
      "title_short": "Log To DB",
      "beacon_id":   241,
      "summary":     [
        "Purpose - Provides finer control over the audit trail itself.",
        "Recommendation - These settings are dependent on your requirements."
      ]
    },
    {
      "slug":        "section_at_file",
      "title":       "Log To File",
      "title_short": "Log To File",
      "beacon_id":   241,
      "summary":     [
        "Purpose - Provides finer control over the audit trail itself.",
        "Recommendation - These settings are dependent on your requirements."
      ]
    },
    {
      "slug":        "section_enable_plugin_feature_audit_trail",
      "title":       "Enable Module: Audit Trail",
      "title_short": "Disable Module",
      "beacon_id":   241,
      "summary":     [
        "Purpose - The Audit Trail is designed so you can look back on events and analyse what happened and what may have gone wrong.",
        "Recommendation - Keep the Audit Trail feature turned on."
      ]
    },
    {
      "slug":   "section_non_ui",
      "hidden": true
    }
  ],
  "options":          [
    {
      "key":         "enable_audit_trail",
      "section":     "section_enable_plugin_feature_audit_trail",
      "advanced":    true,
      "default":     "Y",
      "type":        "checkbox",
      "link_info":   "https://shsec.io/5p",
      "link_blog":   "https://shsec.io/a1",
      "beacon_id":   241,
      "name":        "Enable Audit Trail",
      "summary":     "Enable (or Disable) The Audit Trail module",
      "description": "Un-Checking this option will completely disable the Audit Trail module"
    },
    {
      "key":           "log_level_db",
      "section":       "section_localdb",
      "type":          "multiple_select",
      "default":       [
        "alert",
        "warning",
        "notice"
      ],
      "value_options": [
        {
          "value_key": "disabled",
          "text":      "Logging Disabled"
        },
        {
          "value_key": "alert",
          "text":      "Alert"
        },
        {
          "value_key": "warning",
          "text":      "Warning"
        },
        {
          "value_key": "notice",
          "text":      "Notice"
        },
        {
          "value_key": "info",
          "text":      "Info"
        },
        {
          "value_key": "debug",
          "text":      "Debug"
        }
      ],
      "link_info":     "",
      "link_blog":     "",
      "beacon_id":     375,
      "name":          "Logging Level",
      "summary":       "Logging Level For DB-Based Logs",
      "description":   "Logging Level For DB-Based Logs"
    },
    {
      "key":         "audit_trail_auto_clean",
      "section":     "section_localdb",
      "default":     7,
      "min":         1,
      "type":        "integer",
      "link_info":   "https://shsec.io/a2",
      "link_blog":   "https://shsec.io/a1",
      "beacon_id":   375,
      "name":        "Auto Clean",
      "summary":     "Enable Audit Auto Cleaning",
      "description": "Events older than the number of days specified will be automatically cleaned from the database"
    },
    {
      "key":           "log_level_file",
      "section":       "section_at_file",
      "premium":       true,
      "type":          "multiple_select",
      "default":       [
        "disabled"
      ],
      "value_options": [
        {
          "value_key": "disabled",
          "text":      "Logging Disabled"
        },
        {
          "value_key": "same_as_db",
          "text":      "Same As DB"
        },
        {
          "value_key": "alert",
          "text":      "Alert"
        },
        {
          "value_key": "warning",
          "text":      "Warning"
        },
        {
          "value_key": "notice",
          "text":      "Notice"
        },
        {
          "value_key": "info",
          "text":      "Info"
        },
        {
          "value_key": "debug",
          "text":      "Debug"
        }
      ],
      "link_info":     "",
      "link_blog":     "",
      "beacon_id":     375,
      "name":          "File Logging Level",
      "summary":       "Logging Level For File-Based Logs",
      "description":   "Logging Level For File-Based Logs"
    }
  ],
  "definitions":      {
    "db_handler_classes": {
      "at_logs": "\\FernleafSystems\\Wordpress\\Plugin\\Shield\\Modules\\AuditTrail\\DB\\Logs\\Ops\\Handler",
      "at_meta": "\\FernleafSystems\\Wordpress\\Plugin\\Shield\\Modules\\AuditTrail\\DB\\Meta\\Ops\\Handler"
    },
    "db_table_at_logs":   {
      "slug":           "at_logs",
      "has_updated_at": true,
      "has_created_at": true,
      "has_deleted_at": false,
      "cols_custom":    {
        "req_ref":    {
          "macro_type":  "foreign_key_id",
          "foreign_key": {
            "ref_table": "icwp_wpsf_req_logs"
          }
        },
        "site_id":    {
          "macro_type": "unsigned_int",
          "default":    1,
          "comment":    "Site ID"
        },
        "event_slug": {
          "macro_type": "varchar",
          "comment":    "Event Slug"
        }
      }
    },
    "db_table_at_meta":   {
      "slug":           "at_meta",
      "has_updated_at": false,
      "has_created_at": false,
      "has_deleted_at": false,
      "cols_custom":    {
        "log_ref":    {
          "macro_type":  "foreign_key_id",
          "foreign_key": {
            "ref_table": "icwp_wpsf_at_logs"
          },
          "comment":     "Reference to primary log entry"
        },
        "meta_key":   {
          "macro_type": "varchar",
          "length":     32,
          "comment":    "Meta Key"
        },
        "meta_value": {
          "macro_type": "text",
          "comment":    "Meta Data"
        }
      }
    },
    "max_free_days":      7,
    "events":             {
      "plugin_activated":        {
        "audit_params":   [
          "plugin"
        ],
        "level":          "notice",
        "audit_multiple": true
      },
      "plugin_deactivated":      {
        "audit_params":   [
          "plugin"
        ],
        "level":          "notice",
        "audit_multiple": true
      },
      "plugin_file_edited":      {
        "audit_params": [
          "file"
        ],
        "level":        "warning"
      },
      "plugin_upgraded":         {
        "audit_params":   [
          "plugin",
          "from",
          "to"
        ],
        "level":          "notice",
        "audit_multiple": true
      },
      "theme_activated":         {
        "audit_params": [
          "theme"
        ],
        "level":        "notice"
      },
      "theme_file_edited":       {
        "audit_params": [
          "file"
        ],
        "level":        "warning"
      },
      "theme_upgraded":          {
        "audit_params":   [
          "theme",
          "from",
          "to"
        ],
        "level":          "notice",
        "audit_multiple": true
      },
      "core_updated":            {
        "audit_params": [
          "from",
          "to"
        ],
        "level":        "notice"
      },
      "permalinks_structure":    {
        "audit_params": [
          "from",
          "to"
        ],
        "level":        "warning"
      },
      "post_deleted":            {
        "audit_params":   [
          "title"
        ],
        "level":          "warning",
        "audit_multiple": true
      },
      "post_trashed":            {
        "audit_params":   [
          "title",
          "type"
        ],
        "level":          "warning",
        "audit_multiple": true
      },
      "post_recovered":          {
        "audit_params":   [
          "title",
          "type"
        ],
        "level":          "info",
        "audit_multiple": true
      },
      "post_updated":            {
        "audit_params":   [
          "title",
          "type"
        ],
        "level":          "notice",
        "audit_multiple": true
      },
      "post_published":          {
        "audit_params":   [
          "title",
          "type"
        ],
        "level":          "notice",
        "audit_multiple": true
      },
      "post_unpublished":        {
        "audit_params":   [
          "title",
          "type"
        ],
        "level":          "warning",
        "audit_multiple": true
      },
      "user_login":              {
        "audit_params": [
          "user_login"
        ],
        "level":        "warning"
      },
      "user_login_app":          {
        "audit_params": [
          "user_login"
        ],
        "level":        "warning"
      },
      "user_registered":         {
        "audit_params": [
          "user_login",
          "email"
        ],
        "level":        "alert"
      },
      "user_deleted":            {
        "audit_params":   [
          "user_login",
          "email"
        ],
        "level":          "warning",
        "audit_multiple": true
      },
      "user_deleted_reassigned": {
        "audit_params": [
          "user_login"
        ],
        "level":        "notice"
      },
      "email_attempt_send":      {
        "audit_params":   [
          "to",
          "subject",
          "cc",
          "bcc",
          "bt_file",
          "bt_line"
        ],
        "level":          "info",
        "audit_multiple": true
      }
    }
  }
}