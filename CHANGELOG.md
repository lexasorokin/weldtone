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


