{{-- CKEditor 5 - Latest Stable Version (v43.3.1) --}}
{{-- استخدام CDN الرسمي من CKEditor --}}
<script src="https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.umd.js"></script>
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.css" />

<style>
.ck-editor__editable {
    min-height: 400px;
}
.ck.ck-editor__main > .ck-editor__editable {
    background-color: #fff;
}
.ck-content {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
}
</style>

<script>
// Custom Upload Adapter for Image Upload
class UploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }

    upload() {
        return this.loader.file
            .then(file => new Promise((resolve, reject) => {
                const data = new FormData();
                data.append('upload', file);
                data.append('_token', '{{ csrf_token() }}');

                fetch('{{ route("admin.upload.image") }}', {
                    method: 'POST',
                    body: data
                })
                .then(response => response.json())
                .then(result => {
                    if (result.url) {
                        resolve({
                            default: result.url
                        });
                    } else {
                        reject(result.error || 'Upload failed');
                    }
                })
                .catch(error => {
                    reject(error);
                });
            }));
    }

    abort() {
        // Handle abort
    }
}

function CustomUploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
        return new UploadAdapter(loader);
    };
}

// Initialize CKEditor 5 with Full Features
function initFullCKEditor(selector, height = 500) {
    const {
        ClassicEditor,
        Essentials,
        Paragraph,
        Heading,
        Bold,
        Italic,
        Underline,
        Strikethrough,
        Font,
        Alignment,
        List,
        Link,
        Image,
        ImageCaption,
        ImageStyle,
        ImageToolbar,
        ImageUpload,
        ImageResize,
        Table,
        TableToolbar,
        MediaEmbed,
        BlockQuote,
        Indent,
        IndentBlock,
        Undo,
        SourceEditing
    } = CKEDITOR;

    return ClassicEditor
        .create(document.querySelector(selector), {
            licenseKey: 'GPL', // مفتاح GPL للنسخة المجانية
            plugins: [
                Essentials,
                Paragraph,
                Heading,
                Bold,
                Italic,
                Underline,
                Strikethrough,
                Font,
                Alignment,
                List,
                Link,
                Image,
                ImageCaption,
                ImageStyle,
                ImageToolbar,
                ImageUpload,
                ImageResize,
                Table,
                TableToolbar,
                MediaEmbed,
                BlockQuote,
                Indent,
                IndentBlock,
                Undo,
                SourceEditing,
                CustomUploadAdapterPlugin
            ],
            toolbar: {
                items: [
                    'heading',
                    '|',
                    'fontFamily', 'fontSize', 'fontColor', 'fontBackgroundColor',
                    '|',
                    'bold', 'italic', 'underline', 'strikethrough',
                    '|',
                    'alignment',
                    '|',
                    'numberedList', 'bulletedList',
                    '|',
                    'outdent', 'indent',
                    '|',
                    'link', 'uploadImage', 'insertTable', 'mediaEmbed', 'blockQuote',
                    '|',
                    'sourceEditing',
                    'htmlSource',
                    '|',
                    'undo', 'redo'
                ],
                shouldNotGroupWhenFull: true
            },
            fontSize: {
                options: [
                    9,
                    11,
                    13,
                    'default',
                    17,
                    19,
                    21,
                    27,
                    35
                ],
                supportAllValues: true
            },
            fontFamily: {
                options: [
                    'default',
                    'Arial, Helvetica, sans-serif',
                    'Courier New, Courier, monospace',
                    'Georgia, serif',
                    'Lucida Sans Unicode, Lucida Grande, sans-serif',
                    'Tahoma, Geneva, sans-serif',
                    'Times New Roman, Times, serif',
                    'Trebuchet MS, Helvetica, sans-serif',
                    'Verdana, Geneva, sans-serif'
                ],
                supportAllValues: true
            },
            fontColor: {
                columns: 6,
                documentColors: 12
            },
            fontBackgroundColor: {
                columns: 6,
                documentColors: 12
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                    { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                    { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' }
                ]
            },
            image: {
                toolbar: [
                    'imageTextAlternative',
                    '|',
                    'imageStyle:inline',
                    'imageStyle:block',
                    'imageStyle:side',
                    '|',
                    'toggleImageCaption'
                ],
                resizeOptions: [
                    {
                        name: 'resizeImage:original',
                        label: 'Original',
                        value: null
                    },
                    {
                        name: 'resizeImage:25',
                        label: '25%',
                        value: '25'
                    },
                    {
                        name: 'resizeImage:50',
                        label: '50%',
                        value: '50'
                    },
                    {
                        name: 'resizeImage:75',
                        label: '75%',
                        value: '75'
                    }
                ]
            },
            table: {
                contentToolbar: [
                    'tableColumn',
                    'tableRow',
                    'mergeTableCells'
                ]
            },
            link: {
                decorators: {
                    addTargetToExternalLinks: {
                        mode: 'automatic',
                        callback: url => /^(https?:)?\/\//.test(url),
                        attributes: {
                            target: '_blank',
                            rel: 'noopener noreferrer'
                        }
                    }
                }
            },
            mediaEmbed: {
                previewsInData: true
            },
            sourceEditing: {
                allowCollaborationFeatures: true
            }
        })
        .then(editor => {
            // Set custom height
            editor.ui.view.editable.element.style.minHeight = height + 'px';

            // Add custom styling
            editor.ui.view.editable.element.style.backgroundColor = '#fff';

            // Add custom HTML Source button (always available as fallback)
            editor.ui.componentFactory.add('htmlSource', locale => {
                const view = new CKEDITOR.ui.ButtonView(locale);
                view.set({
                    label: 'HTML Source',
                    icon: '<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 2h16v16H2V2zm2 2v12h12V4H4zm2 2h8v2H6V6zm0 4h8v2H6v-2z" fill="currentColor"/></svg>',
                    tooltip: true,
                    withText: false
                });

                view.on('execute', () => {
                    const currentContent = editor.getData();

                    // Create modal
                    const modal = document.createElement('div');
                    modal.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 10000; display: flex; align-items: center; justify-content: center;';

                    const modalContent = document.createElement('div');
                    modalContent.style.cssText = 'background: white; padding: 25px; border-radius: 8px; width: 90%; max-width: 900px; max-height: 90vh; overflow: auto; box-shadow: 0 10px 40px rgba(0,0,0,0.3);';

                    const title = document.createElement('h3');
                    title.textContent = 'Edit HTML Source';
                    title.style.cssText = 'margin-top: 0; margin-bottom: 15px; color: #1e3a8a;';

                    const textarea = document.createElement('textarea');
                    textarea.value = currentContent;
                    textarea.style.cssText = 'width: 100%; min-height: 450px; font-family: "Courier New", monospace; padding: 15px; border: 2px solid #ddd; border-radius: 4px; font-size: 13px; line-height: 1.5; resize: vertical;';
                    textarea.setAttribute('spellcheck', 'false');

                    const buttonContainer = document.createElement('div');
                    buttonContainer.style.cssText = 'margin-top: 20px; text-align: right; padding-top: 15px; border-top: 1px solid #eee;';

                    const saveBtn = document.createElement('button');
                    saveBtn.textContent = 'Save Changes';
                    saveBtn.style.cssText = 'padding: 10px 25px; background: #1e3a8a; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px; font-weight: 500;';

                    const cancelBtn = document.createElement('button');
                    cancelBtn.textContent = 'Cancel';
                    cancelBtn.style.cssText = 'padding: 10px 25px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;';

                    saveBtn.addEventListener('click', () => {
                        editor.setData(textarea.value);
                        document.body.removeChild(modal);
                    });

                    cancelBtn.addEventListener('click', () => {
                        document.body.removeChild(modal);
                    });

                    // Close on Escape key
                    modal.addEventListener('click', (e) => {
                        if (e.target === modal) {
                            document.body.removeChild(modal);
                        }
                    });

                    modalContent.appendChild(title);
                    modalContent.appendChild(textarea);
                    buttonContainer.appendChild(cancelBtn);
                    buttonContainer.appendChild(saveBtn);
                    modalContent.appendChild(buttonContainer);
                    modal.appendChild(modalContent);
                    document.body.appendChild(modal);

                    // Focus textarea and select all
                    setTimeout(() => {
                        textarea.focus();
                        textarea.select();
                    }, 100);
                });

                return view;
            });

            window.lastEditor = editor; // للوصول للـ editor من خارج الدالة

            return editor;
        })
        .catch(error => {
            console.error('CKEditor initialization error:', error);
            if (typeof toastr !== 'undefined') {
                toastr.error('فشل تحميل المحرر. يرجى تحديث الصفحة.');
            } else {
                alert('Failed to initialize editor. Please refresh the page.');
            }
            throw error;
        });
}
</script>
