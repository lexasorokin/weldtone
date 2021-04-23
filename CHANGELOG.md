Project #11320 - Mesa water various changes

Database changes:

1) go here 
/admin/structure/paragraphs_type/image_with_icon_and_link/form-display open 
Icon with link field settings and set Default paragraph type to None

2) add doc and docx extensions here
/admin/config/media/imce
particularly for admin profile: /admin/config/media/imce/admin

3) increase text trimming size here
/admin/structure/views/view/featured_content/edit/block_1
for Body field in Rewrite result section to 160 characters

4) extend link trimming here /admin/structure/paragraphs_type/menu_item/display 
to 800 characters

5) enable pdfview module
the module allows to view pdf files in browser instead of downloading them by
using the following link format:
for example if you have file path
 http://mesa.ragra.ru/sites/default/files/2021-03/FY-2019-BUDGET.pdf
it can be forced to view in browser using the following path
http://mesa.ragra.ru/pdfview?f=/sites/default/files/2021-03/FY-2019-BUDGET.pdf

6) install and enable ckeditor_youtube module using composer and
following the instructions
https://www.drupal.org/project/ckeditor_youtube

7) install views_conditional module using composer end enable it

8) install pdf_preview_image module using composer end enable it
also install the library
composer require spatie/pdf-to-image  

make sure that imagemagick PHP extension is installed

9) Create Report section taxonomy vocabulary
with machine name report_section


10) Create Report content type (machine name 'report')
with the following fields
Description	body	Text (formatted, long, with summary)	
File	field_report_file	File	
Preview image	field_report_preview_image	Image	
Section	field_report_section	Entity reference

11) Create image style 'Report preview' with machine name report_preview

12) Import view:
uuid: 63c07298-a411-4583-8b0a-263de88db466
langcode: en
status: true
dependencies:
  config:
    - field.storage.node.body
    - field.storage.node.field_report_file
    - field.storage.node.field_report_preview_image
    - field.storage.node.field_report_section
    - image.style.report_preview
    - node.type.report
  module:
    - file
    - node
    - svg_image
    - taxonomy
    - text
    - user
    - views_conditional
id: financial_services
label: 'Financial Services'
module: views
description: ''
tag: ''
base_table: node_field_data
base_field: nid
display:
  default:
    display_plugin: default
    id: default
    display_title: Master
    position: 0
    display_options:
      access:
        type: perm
        options:
          perm: 'access content'
      cache:
        type: tag
        options: {  }
      query:
        type: views_query
        options:
          disable_sql_rewrite: false
          distinct: false
          replica: false
          query_comment: ''
          query_tags: {  }
      exposed_form:
        type: basic
        options:
          submit_button: Apply
          reset_button: false
          reset_button_label: Reset
          exposed_sorts_label: 'Sort by'
          expose_sort_order: true
          sort_asc_label: Asc
          sort_desc_label: Desc
      pager:
        type: none
        options:
          items_per_page: 0
          offset: 0
      style:
        type: default
        options:
          grouping:
            -
              field: field_report_section
              rendered: true
              rendered_strip: false
          row_class: col-lg-6
          default_row_class: true
      row:
        type: fields
      fields:
        field_report_preview_image:
          id: field_report_preview_image
          table: node__field_report_preview_image
          field: field_report_preview_image
          relationship: none
          group_type: group
          admin_label: ''
          label: ''
          exclude: true
          alter:
            alter_text: false
            text: ''
            make_link: false
            path: ''
            absolute: false
            external: false
            replace_spaces: false
            path_case: none
            trim_whitespace: false
            alt: ''
            rel: ''
            link_class: ''
            prefix: ''
            suffix: ''
            target: ''
            nl2br: false
            max_length: 0
            word_boundary: true
            ellipsis: true
            more_link: false
            more_link_text: ''
            more_link_path: ''
            strip_tags: false
            trim: false
            preserve_tags: ''
            html: false
          element_type: ''
          element_class: ''
          element_label_type: ''
          element_label_class: ''
          element_label_colon: false
          element_wrapper_type: ''
          element_wrapper_class: ''
          element_default_classes: true
          empty: ''
          hide_empty: false
          empty_zero: false
          hide_alter_empty: true
          click_sort_column: target_id
          type: image
          settings:
            image_style: report_preview
            image_link: ''
            svg_render_as_image: true
            svg_attributes:
              width: null
              height: null
          group_column: ''
          group_columns: {  }
          group_rows: true
          delta_limit: 0
          delta_offset: 0
          delta_reversed: false
          delta_first_last: false
          multi_type: separator
          separator: ', '
          field_api_classes: false
          plugin_id: field
        title:
          id: title
          table: node_field_data
          field: title
          relationship: none
          group_type: group
          admin_label: ''
          label: ''
          exclude: true
          alter:
            alter_text: false
            text: ''
            make_link: false
            path: ''
            absolute: false
            external: false
            replace_spaces: false
            path_case: none
            trim_whitespace: false
            alt: ''
            rel: ''
            link_class: ''
            prefix: ''
            suffix: ''
            target: ''
            nl2br: false
            max_length: 0
            word_boundary: false
            ellipsis: false
            more_link: false
            more_link_text: ''
            more_link_path: ''
            strip_tags: false
            trim: false
            preserve_tags: ''
            html: false
          element_type: ''
          element_class: ''
          element_label_type: ''
          element_label_class: ''
          element_label_colon: false
          element_wrapper_type: ''
          element_wrapper_class: ''
          element_default_classes: true
          empty: ''
          hide_empty: false
          empty_zero: false
          hide_alter_empty: true
          click_sort_column: value
          type: string
          settings:
            link_to_entity: false
          group_column: value
          group_columns: {  }
          group_rows: true
          delta_limit: 0
          delta_offset: 0
          delta_reversed: false
          delta_first_last: false
          multi_type: separator
          separator: ', '
          field_api_classes: false
          entity_type: node
          entity_field: title
          plugin_id: field
        field_report_file:
          id: field_report_file
          table: node__field_report_file
          field: field_report_file
          relationship: none
          group_type: group
          admin_label: ''
          label: ''
          exclude: true
          alter:
            alter_text: false
            text: ''
            make_link: false
            path: ''
            absolute: false
            external: false
            replace_spaces: false
            path_case: none
            trim_whitespace: false
            alt: ''
            rel: ''
            link_class: ''
            prefix: ''
            suffix: ''
            target: ''
            nl2br: false
            max_length: 0
            word_boundary: true
            ellipsis: true
            more_link: false
            more_link_text: ''
            more_link_path: ''
            strip_tags: false
            trim: false
            preserve_tags: ''
            html: false
          element_type: ''
          element_class: ''
          element_label_type: ''
          element_label_class: ''
          element_label_colon: false
          element_wrapper_type: ''
          element_wrapper_class: ''
          element_default_classes: true
          empty: ''
          hide_empty: false
          empty_zero: false
          hide_alter_empty: true
          click_sort_column: target_id
          type: file_default
          settings:
            use_description_as_link_text: false
          group_column: ''
          group_columns: {  }
          group_rows: true
          delta_limit: 0
          delta_offset: 0
          delta_reversed: false
          delta_first_last: false
          multi_type: separator
          separator: ', '
          field_api_classes: false
          plugin_id: field
        field_report_file_1:
          id: field_report_file_1
          table: node__field_report_file
          field: field_report_file
          relationship: none
          group_type: group
          admin_label: 'FILE URL'
          label: ''
          exclude: true
          alter:
            alter_text: false
            text: ''
            make_link: false
            path: ''
            absolute: false
            external: false
            replace_spaces: false
            path_case: none
            trim_whitespace: false
            alt: ''
            rel: ''
            link_class: ''
            prefix: ''
            suffix: ''
            target: ''
            nl2br: false
            max_length: 0
            word_boundary: true
            ellipsis: true
            more_link: false
            more_link_text: ''
            more_link_path: ''
            strip_tags: false
            trim: false
            preserve_tags: ''
            html: false
          element_type: ''
          element_class: ''
          element_label_type: ''
          element_label_class: ''
          element_label_colon: false
          element_wrapper_type: ''
          element_wrapper_class: ''
          element_default_classes: true
          empty: ''
          hide_empty: false
          empty_zero: false
          hide_alter_empty: true
          click_sort_column: target_id
          type: file_url_plain
          settings: {  }
          group_column: ''
          group_columns: {  }
          group_rows: true
          delta_limit: 0
          delta_offset: 0
          delta_reversed: false
          delta_first_last: false
          multi_type: separator
          separator: ', '
          field_api_classes: false
          plugin_id: field
        field_report_section:
          id: field_report_section
          table: node__field_report_section
          field: field_report_section
          relationship: none
          group_type: group
          admin_label: ''
          label: ''
          exclude: true
          alter:
            alter_text: false
            text: ''
            make_link: false
            path: ''
            absolute: false
            external: false
            replace_spaces: false
            path_case: none
            trim_whitespace: false
            alt: ''
            rel: ''
            link_class: ''
            prefix: ''
            suffix: ''
            target: ''
            nl2br: false
            max_length: 0
            word_boundary: true
            ellipsis: true
            more_link: false
            more_link_text: ''
            more_link_path: ''
            strip_tags: false
            trim: false
            preserve_tags: ''
            html: false
          element_type: ''
          element_class: ''
          element_label_type: ''
          element_label_class: ''
          element_label_colon: false
          element_wrapper_type: ''
          element_wrapper_class: ''
          element_default_classes: true
          empty: ''
          hide_empty: false
          empty_zero: false
          hide_alter_empty: true
          click_sort_column: target_id
          type: entity_reference_label
          settings:
            link: false
          group_column: target_id
          group_columns: {  }
          group_rows: true
          delta_limit: 0
          delta_offset: 0
          delta_reversed: false
          delta_first_last: false
          multi_type: separator
          separator: ', '
          field_api_classes: false
          plugin_id: field
        views_conditional_field:
          id: views_conditional_field
          table: views_conditional
          field: views_conditional_field
          relationship: none
          group_type: group
          admin_label: ''
          label: ''
          exclude: true
          alter:
            alter_text: false
            text: ''
            make_link: false
            path: ''
            absolute: false
            external: false
            replace_spaces: false
            path_case: none
            trim_whitespace: false
            alt: ''
            rel: ''
            link_class: ''
            prefix: ''
            suffix: ''
            target: ''
            nl2br: false
            max_length: 0
            word_boundary: true
            ellipsis: true
            more_link: false
            more_link_text: ''
            more_link_path: ''
            strip_tags: false
            trim: false
            preserve_tags: ''
            html: false
          element_type: ''
          element_class: ''
          element_label_type: ''
          element_label_class: ''
          element_label_colon: false
          element_wrapper_type: ''
          element_wrapper_class: ''
          element_default_classes: true
          empty: ''
          hide_empty: false
          empty_zero: false
          hide_alter_empty: true
          if: field_report_preview_image
          condition: '6'
          equalto: ''
          then: '{{ field_report_preview_image }}'
          or: '{{ field_report_preview_image }}'
          strip_tags: 0
          plugin_id: views_conditional_field
        body:
          id: body
          table: node__body
          field: body
          relationship: none
          group_type: group
          admin_label: ''
          label: ''
          exclude: true
          alter:
            alter_text: false
            text: ''
            make_link: false
            path: ''
            absolute: false
            external: false
            replace_spaces: false
            path_case: none
            trim_whitespace: false
            alt: ''
            rel: ''
            link_class: ''
            prefix: ''
            suffix: ''
            target: ''
            nl2br: false
            max_length: 0
            word_boundary: true
            ellipsis: true
            more_link: false
            more_link_text: ''
            more_link_path: ''
            strip_tags: false
            trim: false
            preserve_tags: ''
            html: false
          element_type: ''
          element_class: ''
          element_label_type: ''
          element_label_class: ''
          element_label_colon: false
          element_wrapper_type: ''
          element_wrapper_class: ''
          element_default_classes: true
          empty: ''
          hide_empty: false
          empty_zero: false
          hide_alter_empty: true
          click_sort_column: value
          type: text_summary_or_trimmed
          settings:
            trim_length: 600
          group_column: value
          group_columns: {  }
          group_rows: true
          delta_limit: 0
          delta_offset: 0
          delta_reversed: false
          delta_first_last: false
          multi_type: separator
          separator: ', '
          field_api_classes: false
          plugin_id: field
        nothing:
          id: nothing
          table: views
          field: nothing
          relationship: none
          group_type: group
          admin_label: ''
          label: ''
          exclude: false
          alter:
            alter_text: true
            text: "<article class=\"zdocs-document zdocs-row\">\t\t\r\n\t<div class=\"gutter-15 row\">\t\t\r\n\t\t\t\t<div class=\"col-lg-3 col-xs-3\">\t\t\t\r\n\t\t\t{{ views_conditional_field }}\r\n\t\t               </div>\r\n\t\t\t\t\r\n\t\t<div class=\"col-lg-9 col-xs-9\">\t\t\t\t\t\t\r\n\t\t\t<h4 class=\"zdocs-documents-header\">{{ title }}</h4>\r\n                               <div class=\"zdocs-documents-description zdocs-bottom\">{{ body }}</div>\r\n\t\t\t\t\t\t\t\t<ul class=\"list-unstyled list-inline zdocs-taskbar\">\r\n\t\t\t\t\t<li>\t\t\r\n\t\t            \t               <a class=\"zdocs-download btn btn-primary btn-xs\" href=\"{{ field_report_file_1 }}\" target=\"_blank\" onclick=\"\">Download</a>\r\n\t\t\t\t\t</li>\r\n\t\t\t\t\t<li>\r\n\t\t\t\t\t\t<a target=\"_blank\" href=\"/pdfview?f={{ field_report_file_1 }}\" class=\"zdocs-download btn btn-primary btn-xs\" title=\"View Fiscal Year 2021 Budget\" onclick=\"\">View</a>\r\n\t\t\t\t\t</li>  \t\t\t\t\t\r\n\t\t\t\t</ul>\r\n\t\t</div>\r\n\t</div>\t\t\r\n</article>"
            make_link: false
            path: ''
            absolute: false
            external: false
            replace_spaces: false
            path_case: none
            trim_whitespace: false
            alt: ''
            rel: ''
            link_class: ''
            prefix: ''
            suffix: ''
            target: ''
            nl2br: false
            max_length: 0
            word_boundary: true
            ellipsis: true
            more_link: false
            more_link_text: ''
            more_link_path: ''
            strip_tags: false
            trim: false
            preserve_tags: ''
            html: false
          element_type: ''
          element_class: ''
          element_label_type: ''
          element_label_class: ''
          element_label_colon: false
          element_wrapper_type: ''
          element_wrapper_class: ''
          element_default_classes: true
          empty: ''
          hide_empty: false
          empty_zero: false
          hide_alter_empty: false
          plugin_id: custom
      filters:
        status:
          value: '1'
          table: node_field_data
          field: status
          plugin_id: boolean
          entity_type: node
          entity_field: status
          id: status
          expose:
            operator: ''
            operator_limit_selection: false
            operator_list: {  }
          group: 1
        type:
          id: type
          table: node_field_data
          field: type
          value:
            report: report
          entity_type: node
          entity_field: type
          plugin_id: bundle
          expose:
            operator_limit_selection: false
            operator_list: {  }
      sorts:
        weight:
          id: weight
          table: taxonomy_term_field_data
          field: weight
          relationship: field_report_section
          group_type: group
          admin_label: ''
          order: ASC
          exposed: false
          expose:
            label: ''
          entity_type: taxonomy_term
          entity_field: weight
          plugin_id: standard
        created:
          id: created
          table: node_field_data
          field: created
          order: DESC
          entity_type: node
          entity_field: created
          plugin_id: date
          relationship: none
          group_type: group
          admin_label: ''
          exposed: false
          expose:
            label: ''
          granularity: second
      title: 'Financial Services'
      header: {  }
      footer: {  }
      empty: {  }
      relationships:
        field_report_section:
          id: field_report_section
          table: node__field_report_section
          field: field_report_section
          relationship: none
          group_type: group
          admin_label: 'field_report_section: Taxonomy term'
          required: true
          plugin_id: standard
      arguments: {  }
      display_extenders: {  }
      css_class: view-financial-services
    cache_metadata:
      max-age: -1
      contexts:
        - 'languages:language_content'
        - 'languages:language_interface'
        - 'user.node_grants:view'
        - user.permissions
      tags:
        - 'config:field.storage.node.body'
        - 'config:field.storage.node.field_report_file'
        - 'config:field.storage.node.field_report_preview_image'
        - 'config:field.storage.node.field_report_section'
  page:
    display_plugin: page
    id: page
    display_title: Page
    position: 1
    display_options:
      display_extenders: {  }
      path: about/financial-services
    cache_metadata:
      max-age: -1
      contexts:
        - 'languages:language_content'
        - 'languages:language_interface'
        - 'user.node_grants:view'
        - user.permissions
      tags:
        - 'config:field.storage.node.body'
        - 'config:field.storage.node.field_report_file'
        - 'config:field.storage.node.field_report_preview_image'
        - 'config:field.storage.node.field_report_section'


13) configure appropriate text format, for example
/admin/config/content/formats/manage/basic_html
Check "Enable image uploads" checkbox in Image section in CKEditor plugin settings
Add "Insert images using Imce File Manager " to toolbar
Remove "Image" button from toolbar (optional)
Enable Align images filter


14) install and enable Views Bulk Operations module
composer require drupal/views_bulk_operations

then update Content view to use it:
uuid: d30b81b3-85ae-48b6-8b14-b77f4780acd9
langcode: en
status: true
dependencies:
  module:
    - node
    - user
    - views_bulk_operations
_core:
  default_config_hash: P7F4DFe8BL31DRVqsvS-ix9KZZqvvaPjZ7zEvGo9yNM
id: content
label: Content
module: node
description: 'Find and manage content.'
tag: default
base_table: node_field_data
base_field: nid
display:
  default:
    display_options:
      access:
        type: perm
        options:
          perm: 'access content overview'
      cache:
        type: tag
      query:
        type: views_query
      exposed_form:
        type: basic
        options:
          submit_button: Filter
          reset_button: true
          reset_button_label: Reset
          exposed_sorts_label: 'Sort by'
          expose_sort_order: true
          sort_asc_label: Asc
          sort_desc_label: Desc
      pager:
        type: full
        options:
          items_per_page: 50
          tags:
            previous: '‹ Previous'
            next: 'Next ›'
            first: '« First'
            last: 'Last »'
      style:
        type: table
        options:
          grouping: {  }
          row_class: ''
          default_row_class: true
          override: true
          sticky: true
          caption: ''
          summary: ''
          description: ''
          columns:
            node_bulk_form: node_bulk_form
            title: title
            type: type
            name: name
            status: status
            changed: changed
            edit_node: edit_node
            delete_node: delete_node
            dropbutton: dropbutton
            timestamp: title
          info:
            node_bulk_form:
              align: ''
              separator: ''
              empty_column: false
              responsive: ''
            title:
              sortable: true
              default_sort_order: asc
              align: ''
              separator: ''
              empty_column: false
              responsive: ''
            type:
              sortable: true
              default_sort_order: asc
              align: ''
              separator: ''
              empty_column: false
              responsive: ''
            name:
              sortable: false
              default_sort_order: asc
              align: ''
              separator: ''
              empty_column: false
              responsive: priority-low
            status:
              sortable: true
              default_sort_order: asc
              align: ''
              separator: ''
              empty_column: false
              responsive: ''
            changed:
              sortable: true
              default_sort_order: desc
              align: ''
              separator: ''
              empty_column: false
              responsive: priority-low
            edit_node:
              sortable: false
              default_sort_order: asc
              align: ''
              separator: ''
              empty_column: false
              responsive: ''
            delete_node:
              sortable: false
              default_sort_order: asc
              align: ''
              separator: ''
              empty_column: false
              responsive: ''
            dropbutton:
              sortable: false
              default_sort_order: asc
              align: ''
              separator: ''
              empty_column: false
              responsive: ''
            timestamp:
              sortable: false
              default_sort_order: asc
              align: ''
              separator: ''
              empty_column: false
              responsive: ''
          default: changed
          empty_table: true
      row:
        type: fields
      fields:
        views_bulk_operations_bulk_form:
          id: views_bulk_operations_bulk_form
          table: views
          field: views_bulk_operations_bulk_form
          relationship: none
          group_type: group
          admin_label: ''
          label: 'Views bulk operations'
          exclude: false
          alter:
            alter_text: false
            text: ''
            make_link: false
            path: ''
            absolute: false
            external: false
            replace_spaces: false
            path_case: none
            trim_whitespace: false
            alt: ''
            rel: ''
            link_class: ''
            prefix: ''
            suffix: ''
            target: ''
            nl2br: false
            max_length: 0
            word_boundary: true
            ellipsis: true
            more_link: false
            more_link_text: ''
            more_link_path: ''
            strip_tags: false
            trim: false
            preserve_tags: ''
            html: false
          element_type: ''
          element_class: ''
          element_label_type: ''
          element_label_class: ''
          element_label_colon: true
          element_wrapper_type: ''
          element_wrapper_class: ''
          element_default_classes: true
          empty: ''
          hide_empty: false
          empty_zero: false
          hide_alter_empty: true
          batch: true
          batch_size: 10
          form_step: true
          buttons: false
          clear_on_exposed: true
          action_title: Action
          selected_actions:
            -
              action_id: node_publish_action
              preconfiguration:
                add_confirmation: 0
            -
              action_id: node_save_action
              preconfiguration:
                add_confirmation: 0
            -
              action_id: node_make_unsticky_action
              preconfiguration:
                add_confirmation: 0
            -
              action_id: node_make_sticky_action
              preconfiguration:
                add_confirmation: 0
            -
              action_id: node_unpublish_action
              preconfiguration:
                add_confirmation: 0
            -
              action_id: node_unpublish_by_keyword_action
              preconfiguration:
                add_confirmation: 0
            -
              action_id: node_promote_action
              preconfiguration:
                add_confirmation: 0
            -
              action_id: node_assign_owner_action
              preconfiguration:
                add_confirmation: 0
            -
              action_id: node_unpromote_action
              preconfiguration:
                add_confirmation: 0
            -
              action_id: simplenews_send_action
              preconfiguration:
                add_confirmation: 0
            -
              action_id: simplenews_stop_action
              preconfiguration:
                add_confirmation: 0
            -
              action_id: views_bulk_operations_delete_entity
            -
              action_id: 'entity:publish_action:node'
              preconfiguration:
                add_confirmation: 0
            -
              action_id: 'entity:unpublish_action:node'
              preconfiguration:
                add_confirmation: 0
            -
              action_id: 'entity:save_action:node'
              preconfiguration:
                add_confirmation: 0
          force_selection_info: 0
          plugin_id: views_bulk_operations_bulk_form
        title:
          id: title
          table: node_field_data
          field: title
          label: Title
          exclude: false
          alter:
            alter_text: false
          element_class: ''
          element_default_classes: true
          empty: ''
          hide_empty: false
          empty_zero: false
          hide_alter_empty: true
          entity_type: node
          entity_field: title
          type: string
          settings:
            link_to_entity: true
          plugin_id: field
        type:
          id: type
          table: node_field_data
          field: type
          relationship: none
          group_type: group
          admin_label: ''
          label: 'Content type'
          exclude: false
          alter:
            alter_text: false
            text: ''
            make_link: false
            path: ''
            absolute: false
            external: false
            replace_spaces: false
            path_case: none
            trim_whitespace: false
            alt: ''
            rel: ''
            link_class: ''
            prefix: ''
            suffix: ''
            target: ''
            nl2br: false
            max_length: 0
            word_boundary: true
            ellipsis: true
            more_link: false
            more_link_text: ''
            more_link_path: ''
            strip_tags: false
            trim: false
            preserve_tags: ''
            html: false
          element_type: ''
          element_class: ''
          element_label_type: ''
          element_label_class: ''
          element_label_colon: true
          element_wrapper_type: ''
          element_wrapper_class: ''
          element_default_classes: true
          empty: ''
          hide_empty: false
          empty_zero: false
          hide_alter_empty: true
          click_sort_column: target_id
          type: entity_reference_label
          settings:
            link: false
          group_column: target_id
          group_columns: {  }
          group_rows: true
          delta_limit: 0
          delta_offset: 0
          delta_reversed: false
          delta_first_last: false
          multi_type: separator
          separator: ', '
          field_api_classes: false
          entity_type: node
          entity_field: type
          plugin_id: field
        name:
          id: name
          table: users_field_data
          field: name
          relationship: uid
          label: Author
          exclude: false
          alter:
            alter_text: false
          element_class: ''
          element_default_classes: true
          empty: ''
          hide_empty: false
          empty_zero: false
          hide_alter_empty: true
          plugin_id: field
          type: user_name
          entity_type: user
          entity_field: name
        status:
          id: status
          table: node_field_data
          field: status
          label: Status
          exclude: false
          alter:
            alter_text: false
          element_class: ''
          element_default_classes: true
          empty: ''
          hide_empty: false
          empty_zero: false
          hide_alter_empty: true
          type: boolean
          settings:
            format: custom
            format_custom_true: Published
            format_custom_false: Unpublished
          plugin_id: field
          entity_type: node
          entity_field: status
        changed:
          id: changed
          table: node_field_data
          field: changed
          label: Updated
          exclude: false
          alter:
            alter_text: false
          element_class: ''
          element_default_classes: true
          empty: ''
          hide_empty: false
          empty_zero: false
          hide_alter_empty: true
          type: timestamp
          settings:
            date_format: short
            custom_date_format: ''
            timezone: ''
          plugin_id: field
          entity_type: node
          entity_field: changed
        operations:
          id: operations
          table: node
          field: operations
          relationship: none
          group_type: group
          admin_label: ''
          label: Operations
          exclude: false
          alter:
            alter_text: false
            text: ''
            make_link: false
            path: ''
            absolute: false
            external: false
            replace_spaces: false
            path_case: none
            trim_whitespace: false
            alt: ''
            rel: ''
            link_class: ''
            prefix: ''
            suffix: ''
            target: ''
            nl2br: false
            max_length: 0
            word_boundary: true
            ellipsis: true
            more_link: false
            more_link_text: ''
            more_link_path: ''
            strip_tags: false
            trim: false
            preserve_tags: ''
            html: false
          element_type: ''
          element_class: ''
          element_label_type: ''
          element_label_class: ''
          element_label_colon: true
          element_wrapper_type: ''
          element_wrapper_class: ''
          element_default_classes: true
          empty: ''
          hide_empty: false
          empty_zero: false
          hide_alter_empty: true
          destination: true
          plugin_id: entity_operations
      filters:
        title:
          id: title
          table: node_field_data
          field: title
          relationship: none
          group_type: group
          admin_label: ''
          operator: contains
          value: ''
          group: 1
          exposed: true
          expose:
            operator_id: title_op
            label: Title
            description: ''
            use_operator: false
            operator: title_op
            identifier: title
            required: false
            remember: false
            multiple: false
            remember_roles:
              authenticated: authenticated
              anonymous: '0'
              administrator: '0'
            operator_limit_selection: false
            operator_list: {  }
          is_grouped: false
          group_info:
            label: ''
            description: ''
            identifier: ''
            optional: true
            widget: select
            multiple: false
            remember: false
            default_group: All
            default_group_multiple: {  }
            group_items: {  }
          plugin_id: string
          entity_type: node
          entity_field: title
        type:
          id: type
          table: node_field_data
          field: type
          relationship: none
          group_type: group
          admin_label: ''
          operator: in
          value: {  }
          group: 1
          exposed: true
          expose:
            operator_id: type_op
            label: 'Content type'
            description: ''
            use_operator: false
            operator: type_op
            identifier: type
            required: false
            remember: false
            multiple: false
            remember_roles:
              authenticated: authenticated
              anonymous: '0'
              administrator: '0'
            reduce: false
            operator_limit_selection: false
            operator_list: {  }
          is_grouped: false
          group_info:
            label: ''
            description: ''
            identifier: ''
            optional: true
            widget: select
            multiple: false
            remember: false
            default_group: All
            default_group_multiple: {  }
            group_items: {  }
          plugin_id: bundle
          entity_type: node
          entity_field: type
        status:
          id: status
          table: node_field_data
          field: status
          relationship: none
          group_type: group
          admin_label: ''
          operator: '='
          value: '1'
          group: 1
          exposed: true
          expose:
            operator_id: ''
            label: Status
            description: ''
            use_operator: false
            operator: status_op
            identifier: status
            required: false
            remember: false
            multiple: false
            remember_roles:
              authenticated: authenticated
            operator_limit_selection: false
            operator_list: {  }
          is_grouped: true
          group_info:
            label: 'Published status'
            description: ''
            identifier: status
            optional: true
            widget: select
            multiple: false
            remember: false
            default_group: All
            default_group_multiple: {  }
            group_items:
              1:
                title: Published
                operator: '='
                value: '1'
              2:
                title: Unpublished
                operator: '='
                value: '0'
          plugin_id: boolean
          entity_type: node
          entity_field: status
        langcode:
          id: langcode
          table: node_field_data
          field: langcode
          relationship: none
          group_type: group
          admin_label: ''
          operator: in
          value: {  }
          group: 1
          exposed: true
          expose:
            operator_id: langcode_op
            label: Language
            description: ''
            use_operator: false
            operator: langcode_op
            identifier: langcode
            required: false
            remember: false
            multiple: false
            remember_roles:
              authenticated: authenticated
              anonymous: '0'
              administrator: '0'
            reduce: false
            operator_limit_selection: false
            operator_list: {  }
          is_grouped: false
          group_info:
            label: ''
            description: ''
            identifier: ''
            optional: true
            widget: select
            multiple: false
            remember: false
            default_group: All
            default_group_multiple: {  }
            group_items: {  }
          plugin_id: language
          entity_type: node
          entity_field: langcode
        status_extra:
          id: status_extra
          table: node_field_data
          field: status_extra
          operator: '='
          value: false
          plugin_id: node_status
          group: 1
          entity_type: node
          expose:
            operator_limit_selection: false
            operator_list: {  }
      sorts: {  }
      title: Content
      empty:
        area_text_custom:
          id: area_text_custom
          table: views
          field: area_text_custom
          empty: true
          content: 'No content available.'
          plugin_id: text_custom
      arguments: {  }
      relationships:
        uid:
          id: uid
          table: node_field_data
          field: uid
          admin_label: author
          required: true
          plugin_id: standard
      show_admin_links: false
      filter_groups:
        operator: AND
        groups:
          1: AND
      display_extenders: {  }
    display_plugin: default
    display_title: Master
    id: default
    position: 0
    cache_metadata:
      contexts:
        - 'languages:language_content'
        - 'languages:language_interface'
        - url
        - url.query_args
        - user
        - 'user.node_grants:view'
        - user.permissions
      max-age: 0
      tags: {  }
  page_1:
    display_options:
      path: admin/content/node
      menu:
        type: 'default tab'
        title: Content
        description: ''
        menu_name: admin
        weight: -10
        context: ''
      tab_options:
        type: normal
        title: Content
        description: 'Find and manage content'
        menu_name: admin
        weight: -10
      display_extenders: {  }
    display_plugin: page
    display_title: Page
    id: page_1
    position: 1
    cache_metadata:
      contexts:
        - 'languages:language_content'
        - 'languages:language_interface'
        - url
        - url.query_args
        - user
        - 'user.node_grants:view'
        - user.permissions
      max-age: 0
      tags: {  }

15) add Menu Title field that will replace old short menu title field
/admin/structure/paragraphs_type/menu/fields
the machine name of the new field should be field_menu_title

16) enable mesa_misc module

17) go to /admin/content
select all items in the view
choose "Save content" action and apply to selected items

then go to /admin/structure/paragraphs_type/menu/fields
and delete old Menu title field (field_latitude)

the changes also may require flushing all caches or even truncate all cache tables in database