<script lang="ts" setup>
import {
  ClassicEditor,
  Essentials,
  Heading,
  Bold,
  Italic,
  Link,
  List,
  Indent,
  BlockQuote,
  Table,
  MediaEmbed,
  Undo,
  Paragraph,
  HtmlEmbed,
  SourceEditing
} from 'ckeditor5';
import { Ckeditor as CKEditor } from '@ckeditor/ckeditor5-vue';
import 'ckeditor5/ckeditor5.css';
import 'ckeditor5/translations/ar.js';

interface Props {
  modelValue?: string;
  dir?: 'rtl' | 'ltr';
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: '',
  dir: 'rtl',
});

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void;
}>();

const editorData = ref(props.modelValue);

let isPainting = false;
let copiedAttributes: any = null;
let painterButton: any = null;
let currentEditor: any = null;

const mouseupHandler = (event: MouseEvent) => {
  if (isPainting && copiedAttributes && currentEditor) {
    setTimeout(() => {
      const selection = currentEditor.model.document.selection;
      if (!selection.isCollapsed) {
        currentEditor.model.change((writer: any) => {
          const ranges = Array.from(selection.getRanges());
          for (const range of ranges) {
            for (const [key, value] of copiedAttributes) {
              writer.setAttribute(key, value, range);
            }
          }
        });
        isPainting = false;
        if (painterButton) painterButton.isOn = false;
        copiedAttributes = null;
      }
    }, 50);
  }
};

window.addEventListener('mouseup', mouseupHandler);

onUnmounted(() => {
  window.removeEventListener('mouseup', mouseupHandler);
});

function getListType(block: any): string | null {
  if (block.hasAttribute('listType')) {
    return block.getAttribute('listType');
  }
  if (block.name === 'listItem') {
    return 'unknown-list';
  }
  return null;
}

function CustomIndentPlugin(editor: any) {
  editor.model.schema.extend('$block', { allowAttributes: 'customIndent' });

  editor.conversion.for('downcast').attributeToAttribute({
    model: 'customIndent',
    view: (modelAttributeValue: any) => {
      const val = parseInt(modelAttributeValue, 10) || 0;
      return {
        key: 'style',
        value: { 'margin-inline-start': val * 40 + 'px' },
      };
    },
  });

  editor.conversion.for('upcast').attributeToAttribute({
    view: {
      styles: { 'margin-inline-start': /.*/ },
    },
    model: {
      key: 'customIndent',
      value: (viewElement: any) => {
        const margin = parseInt(viewElement.getStyle('margin-inline-start') || '0', 10);
        return Math.floor(margin / 40);
      },
    },
  });
}

function TabToSpacesPlugin(editor: any) {
  editor.editing.view.document.on(
    'keydown',
    (evt: any, data: any) => {
      if (data.keyCode !== 9) return;

      const selection = editor.model.document.selection;
      const isShift = data.shiftKey;

      const insideTable = selection.focus?.findAncestor('tableCell');
      if (insideTable) return;

      const blocks = Array.from(selection.getSelectedBlocks());
      if (blocks.length === 0) return;

      // If we are inside a list, we try executing the native CKEditor indent commands
      const isList = blocks.some((block: any) => getListType(block) !== null);
      if (isList) {
        const commandName = isShift ? 'outdent' : 'indent';
        
        // Use CKEditor's native list indentation which properly creates nested lists (moves bullets)
        if (editor.commands.get(commandName)?.isEnabled) {
          editor.execute(commandName);
        }
        
        evt.stop();
        data.preventDefault();
        return;
      }

      for (const block of blocks as any[]) {
        editor.model.change((writer: any) => {
          const currentIndent = parseInt(block.getAttribute('customIndent') || '0', 10);
          if (isShift) {
            if (currentIndent > 0) {
              writer.setAttribute('customIndent', currentIndent - 1, block);
            }
          } else {
            writer.setAttribute('customIndent', currentIndent + 1, block);
          }
        });
      }

      editor.model.change((writer: any) => {
        const newRanges = (blocks as any[]).map((b) => writer.createRangeOn(b));
        writer.setSelection(newRanges);
      });

      evt.stop();
      data.preventDefault();
    },
    { priority: 'high' }
  );
}

function DirectionPlugin(editor: any) {
  editor.model.schema.extend('$block', { allowAttributes: 'dir' });
  editor.conversion.for('downcast').attributeToAttribute({
    model: 'dir',
    view: (modelAttributeValue: any) => ({ key: 'dir', value: modelAttributeValue }),
  });
  editor.conversion.for('upcast').attributeToAttribute({
    view: { key: 'dir' },
    model: 'dir',
  });

  const createDirectionButton = (dir: 'ltr' | 'rtl', label: string) => {
    editor.ui.componentFactory.add(dir, (locale: any) => {
      const sampleButton = editor.ui.componentFactory.create('bold');
      const ButtonView = (sampleButton as any).constructor;
      const button = new ButtonView(locale);

      button.set({
        label: label,
        withText: true,
        tooltip: true,
      });

      button.on('execute', () => {
        editor.model.change((writer: any) => {
          const blocks = Array.from(editor.model.document.selection.getSelectedBlocks());
          for (const block of blocks) {
            writer.setAttribute('dir', dir, block as any);
          }
        });
        editor.editing.view.focus();
      });

      return button;
    });
  };

  createDirectionButton('ltr', 'LTR');
  createDirectionButton('rtl', 'RTL');
}

const editorConfig = computed(() => ({
  licenseKey: 'GPL',
  plugins: [
    Essentials,
    Paragraph,
    Heading,
    Bold,
    Italic,
    Link,
    List,
    Indent,
    BlockQuote,
    Table,
    MediaEmbed,
    Undo,
    HtmlEmbed,
    SourceEditing,
    CustomIndentPlugin,
    TabToSpacesPlugin,
    DirectionPlugin
  ],
  toolbar: [
    'heading',
    '|',
    'bold',
    'italic',
    'link',
    'bulletedList',
    'numberedList',
    '|',
    'outdent',
    'indent',
    '|',
    'blockQuote',
    'insertTable',
    'mediaEmbed',
    '|',
    'ltr',
    'rtl',
    '|',
    'undo',
    'redo',
    '|',
    'sourceEditing',
  ],
  language: {
    ui: 'ar',
    content: props.dir === 'ltr' ? 'en' : 'ar'
  },
  pasteFromWordRemoveFontStyles: false,
  htmlEmbed: {
    showPreviews: true,
  },
  mediaEmbed: {
    previewsInData: true,
    extraProviders: [
      {
        name: 'all_iframes',
        url: [
          /^http:\/\/196\.1\.226\.242\/(.+)/,
          /^https?:\/\/(.+)/
        ],
        html: (match: any) => {
          const url = match[0];
          return (
            '<div style="position: relative; padding-bottom: 100%; height: 0; padding-bottom: 56.2493%;">' +
            `<iframe src="${url}" ` +
            'style="position: absolute; width: 100%; height: 100%; top: 0; left: 0;" ' +
            'frameborder="0" allowfullscreen>' +
            '</iframe>' +
            '</div>'
          );
        }
      }
    ]
  }
}));

watch(
  () => props.modelValue,
  (newVal) => {
    if (newVal !== editorData.value) {
      editorData.value = newVal || '';
    }
  }
);

watch(editorData, (newValue) => {
  emit('update:modelValue', newValue);
});
</script>

<template>
  <div class="fg-black" style="--ck-border-radius: 0.25rem">
    <CKEditor :editor="ClassicEditor" v-model="editorData" :config="editorConfig" />
  </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');

.ck-editor__editable {
  min-height: 100px;
  font-weight: normal;
  font-family: 'Cairo', sans-serif !important;
  font-size: 14px !important;
  line-height: 1.6 !important;
}

.ck-content ul,
.ck-content ol {
  padding: 0 1em;
}

.ck-content [dir='ltr'] {
  text-align: left !important;
  direction: ltr !important;
}

.ck-content [dir='rtl'] {
  text-align: right !important;
  direction: rtl !important;
}

.ck-content ul:has([dir='ltr']),
.ck-content ol:has([dir='ltr']) {
  direction: ltr !important;
  padding-left: 2em;
  padding-right: 0;
}

.ck-content ul:has([dir='rtl']),
.ck-content ol:has([dir='rtl']) {
  direction: rtl !important;
  padding-right: 2em;
  padding-left: 0;
}

/* Fix z-index for CKEditor dialogs/panels inside Vuetify modals */
.ck-body-wrapper {
  z-index: 99999 !important;
}

:root {
  --ck-z-default: 99999 !important;
  --ck-z-panel: 99999 !important;
}
</style>