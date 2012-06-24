<?php
/*
Plugin Name: Toscho’s Media Artist Field
Description: Adds two field to attachments – Artist and Artist URL – and adds this information to captions.
Version:     0.1
Author:      Thomas Scholz
Author URI:  http://toscho.de
Plugin URI:  http://wordpress.stackexchange.com/a/3108
Created:     19.09.2010
*/

class Toscho_Media_Artist
{
    public
        $fields = array (
            'artist_name' => array (
                'public' => 'toscho_artist_name'
            ,   'hidden' => '_toscho_artist_name'
            ,   'label'  => 'Artist Name'
            )
        ,   'artist_url' => array (
                'public' => 'toscho_artist_url'
            ,   'hidden' => '_toscho_artist_url'
            ,   'label'  => 'Artist URL'
            )
        )
        // Maybe its own field?
    ,   $caption_prefix
    ,   $br_before = TRUE;

    public function __construct(
        $fields         = array()
    ,   $caption_prefix = 'Source: '
    ,   $br_before      = TRUE
    )
    {
        $this->fields         = array_merge($this->fields, $fields);
        $this->caption_prefix = $caption_prefix;
        $this->br_before      = (bool) $br_before;

        $this->set_filter();
    }

    public function set_filter()
    {
        add_filter(
            'attachment_fields_to_edit'
        ,   array ( $this, 'add_fields' )
        ,   15
        ,   2
        );
        add_filter(
            'attachment_fields_to_save'
        ,   array ( $this, 'save_fields' )
        ,   10
        ,   2
        );
        add_filter(
            'img_caption_shortcode'
        ,   array ( $this, 'caption_filter' )
        ,   1
        ,   3
        );
    }

    public function add_fields($form_fields, $post)
    {
        foreach ( $this->fields as $field)
        {
            $form_fields[ $field['public'] ]['label'] = $field['label'];
            $form_fields[ $field['public'] ]['input'] = 'text';
            $form_fields[ $field['public'] ]['value'] = get_post_meta(
                $post->ID
            ,   $field['hidden']
            ,   TRUE
            );
        }
        return $form_fields;
    }

    public function save_fields($post, $attachment)
    {
        foreach ( $this->fields as $field)
        {
            if ( isset ( $attachment[ $field['public'] ]) )
            {
                update_post_meta(
                    $post['ID']
                ,   $field['hidden']
                ,   $attachment[ $field['public'] ]
                );
            }
        }

        return $post;
    }

    public function caption_filter($empty, $attr, $content = '')
    {
        /* Typical input:
         * [caption id="attachment_525" align="aligncenter"
         * width="300" caption="The caption."]
         * <a href="http://example.com/2008/images-test/albeo-screengrab/"
         * rel="attachment wp-att-525"><img
         * src="http://example.com/uploads/2010/08/albeo-screengrab4.jpg?w=300"
         * alt="" title="albeo-screengrab" width="300" height="276"
         * class="size-medium wp-image-525" /></a>[/caption]
         */
        extract(
            shortcode_atts(
                array (
                    'id'        => ''
                ,   'align'     => 'alignnone'
                ,   'width'     => ''
                ,   'caption'   => ''
                ,   'nocredits' => '0'
                )
            ,   $attr
            )
        );

        // Let WP handle these cases.
        if ( empty ($id ) or 1 == $nocredits )
        {
            return '';
        }

        if ( 1 > (int) $width || empty ( $caption ) )
        {
            return $content;
        }

        if ( ! empty ( $id ) )
        {
            // Example: attachment_525
            $html_id     = 'id="' . esc_attr($id) . '" ';
            $tmp         = explode('_', $id);
            $id          = end($tmp);

            $sub_caption = '';
            $artist_name = get_post_meta($id, $this->fields['artist_name']['hidden'], TRUE);
            $artist_url  = get_post_meta($id, $this->fields['artist_url']['hidden'], TRUE);

            // Okay, at least one value.
            if ( '' != $artist_name . $artist_url )
            {
                $sub_caption .= $this->br_before ? '<br />' : '';
                $sub_caption .= '<span class="media-artist">' . $this->caption_prefix;

                // No name given. We use the shortened URL.
                if ( '' == $artist_name )
                {
                    $sub_caption .= '<a rel="author" href="'
                        . $artist_url . '">'
                        . $this->short_url($artist_url)
                        . '</a>';
                } // We have just the name.
                elseif ( '' == $artist_url )
                {
                    $sub_caption .= $artist_name;
                } // We have both.
                else
                {
                    $sub_caption .= '<a rel="author" href="'
                        . $artist_url . '">'
                        . $artist_name
                        . '</a>';
                }

                $sub_caption .= '</span>';
            }

            $caption .= $sub_caption;
        }

        return '<div ' . $html_id . 'class="wp-caption ' . esc_attr($align)
        . '" style="width: ' . (10 + (int) $width) . 'px">'
        . do_shortcode( $content ) . '<p class="wp-caption-text">'
        . $caption . '</p></div>';
    }

    public function short_url($url, $max_length=20)
    {
        $real_length = mb_strlen($url, 'UTF-8');

        if ( $real_length <= $max_length )
        {
            return $url;
        }

        $keep = round( $max_length / 2 ) - 1;

        return mb_substr($url, 0, $keep, 'UTF-8') . '…'
            . mb_substr($url, -$keep, $real_length, 'UTF-8');
    }
    # @todo uninstall
}