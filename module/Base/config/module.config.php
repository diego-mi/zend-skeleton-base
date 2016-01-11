<?php
namespace Base;

return [
    'view_helper_config' => array(
        'flashmessenger' => array(
            'message_open_format' =>
                '<div%s>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <ul><li>',
            'message_close_string' => '</li></ul></div>',
            'message_separator_string' => '</li><li>'
        )
    )
];
