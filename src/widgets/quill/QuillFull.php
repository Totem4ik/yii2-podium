<?php

namespace bizley\podium\widgets\quill;

use bizley\quill\Quill;
use bizley\quill\QuillAsset;
use Yii;


/**
 * Podium Quill widget with full toolbar.
 *
 * @author Paweł Bizley Brzozowski <pawel@positive.codes>
 * @since 0.2
 */
class QuillFull extends Quill
{
    /**
     * @var bool|string|array Toolbar buttons.
     */
    public $toolbarOptions = [
        [['align' => []], ['size' => ['small', false, 'large', 'huge']], 'bold', 'italic', 'underline', 'strike'],
        [['color' => []], ['background' => []]],
        [['header' => [1, 2, 3, 4, 5, 6, false]], ['script' => 'sub'], ['script' => 'super']],
        ['blockquote', 'code-block'],
        [['list' => 'ordered'], ['list' => 'bullet']],
        ['link', 'image',
            //'video'
        ],
        ['emoji']
        //['clean']
    ];

    /**
     * @var array Collection of modules to include and respective options.
     */
    public $modules = ['syntax' => true,
        'toolbar_emoji' => true,

    ];

    /**
     * @var string Highlight.js stylesheet to fetch from https://cdnjs.cloudflare.com
     */
    public $highlightStyle = 'github-gist.min.css';

    /**
     * @var string Additional JS code to be called with the editor.
     * @since 0.3
     */
//    public $js = "{quill}.getModule('toolbar').addHandler('image',imageHandler);function imageHandler(){var range=this.quill.getSelection();var value=prompt('URL:');this.quill.insertEmbed(range.index,'image',value,Quill.sources.USER);};";


    public $js = "
    $( document ).ready(function() {
     {quill}.getModule('toolbar').addHandler('image',imageHandler);
          
     var quillEmojii={quill}.getModule('toolbar_emoji');    
  $('.ql-emoji').click(function() {  
    $('.ql-editor').focus();
    quillEmojii.checkPalatteExist();   
   });
  
      function selectLocalImage() {
      const input = document.createElement('input');
      input.setAttribute('type', 'file');
      input.click();

      // Listen upload local image and save to server
      input.onchange = () => {
        const file = input.files[0];

        // file type is only image.
        if (/^image\//.test(file.type)) {
          saveToServer(file);
        } else {
          console.warn('You could only upload images.');
        }
      };
    }

    /**
     * Step2. save to server
     *
     * @param {File} file
     */
    function saveToServer(file) {
   const fd = new FormData();
      fd.append('UploadImage[imageFile]', file);
      const xhr = new XMLHttpRequest();
      xhr.open('POST', '/clinic/upload-file', true);
      xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name=csrf-token]').attr(\"content\"));
      xhr.onload = () => {
        if (xhr.status === 200) {
        
          const url = xhr.responseText;
          insertToEditor(url);
        }
      };
      xhr.send(fd);       
    }

    /**
     * Step3. insert image url to rich editor.
     *
     * @param {string} url
     */
    function insertToEditor(url) {
      // push image url to rich editor.
      const range = {quill}.getSelection();
      {quill}.insertEmbed(range.index, 'image', url,Quill.sources.USER);
    }
    
    function imageHandler(){
      selectLocalImage();    }
    ;
    });
    ";

    public function registerClientScript()
    {
        $view = $this->view;

        return parent::registerClientScript();
    }
}
