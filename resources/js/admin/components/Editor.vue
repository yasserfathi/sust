<script lang="ts" setup>
import { ref, watch, onUnmounted } from 'vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { component as CKEditor } from '@ckeditor/ckeditor5-vue';
import '@ckeditor/ckeditor5-build-classic/build/translations/ar';

interface Props {
  modelValue?: string;
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: '',
});

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void;
}>();

const editorData = ref(props.modelValue);

let isPainting = false;
let copiedAttributes: any = null;
let painterButton: any = null;
let currentEditor: any = null;

const mouseupHandler = () => {
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

function FormatPainterPlugin(editor: any) {
  currentEditor = editor;
  editor.ui.componentFactory.add('formatPainter', (locale: any) => {
    const sampleButton = editor.ui.componentFactory.create('bold');
    const ButtonView = (sampleButton as any).constructor;
    const button = new ButtonView(locale);

    painterButton = button;

    const brushIcon = '<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M15 3.5a2.5 2.5 0 0 0-3.5 0L5 10v4h4l6.5-6.5a2.5 2.5 0 0 0 0-3.5z"/><path d="M5 15l-1.5 3.5c-.3.7.2 1.5 1 1.5h1c.8 0 1.3-.8 1-1.5L5 15z"/></svg>';

    button.set({
      label: 'Format Painter',
      icon: brushIcon,
      tooltip: true,
      isOn: false,
    });

    button.on('execute', () => {
      if (isPainting) {
        isPainting = false;
        button.isOn = false;
        copiedAttributes = null;
      } else {
        copiedAttributes = Array.from(editor.model.document.selection.getAttributes());
        isPainting = true;
        button.isOn = true;
      }
    });

    return button;
  });
}

/**
 * Returns the list type ('bulleted' | 'numbered' | 'unknown-list') of a model
 * block, or null if the block is not part of a list.
 */
function getListType(block: any): string | null {
  if (block.hasAttribute('listType')) {
    return block.getAttribute('listType');
  }
  if (block.name === 'listItem') {
    // Legacy list item without explicit listType attribute
    return 'unknown-list';
  }
  return null;
}

function CustomIndentPlugin(editor: any) {
  editor.model.schema.extend('$block', { allowAttributes: 'customIndent' });

  // IMPORTANT: 'attributeToStyle' / 'styleToAttribute' are NOT real methods
  // on CKEditor5's Conversion API. Calling them throws at plugin init time,
  // which aborts the whole editor's initialization silently — this was the
  // root cause of Tab not working for either plain text or bulleted lists.
  // The correct helper is 'attributeToAttribute' with key: 'style'.
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
      if (data.keyCode !== 9) return; // Only handle Tab key

      const selection = editor.model.document.selection;
      const isShift = data.shiftKey;

      // Don't hijack Tab inside tables - let CKEditor's table navigation work
      const insideTable = selection.focus?.findAncestor('tableCell');
      if (insideTable) return;

      const blocks = Array.from(selection.getSelectedBlocks());
      if (blocks.length === 0) return;

      // Group contiguous blocks by whether they're list items, so each group
      // gets the indent strategy appropriate to it.
      const groups: any[][] = [];
      let currentGroup: any[] = [];
      let currentKey: string | null = null;

      for (const block of blocks as any[]) {
        const key = getListType(block) ?? 'plain';
        if (currentGroup.length === 0) {
          currentKey = key;
          currentGroup.push(block);
        } else if (key === currentKey) {
          currentGroup.push(block);
        } else {
          groups.push([...currentGroup]);
          currentGroup = [block];
          currentKey = key;
        }
      }
      if (currentGroup.length > 0) groups.push(currentGroup);

      for (let i = groups.length - 1; i >= 0; i--) {
        const group = groups[i];
        const isList = getListType(group[0]) !== null;

        if (isList) {
          // Real list nesting: use CKEditor's own indent/outdent commands so
          // sub-bullets get created/removed properly (not just a visual shift).
          editor.model.change((writer: any) => {
            const groupRanges = group.map((b) => writer.createRangeOn(b));
            writer.setSelection(groupRanges);
          });
          const commandName = isShift ? 'outdent' : 'indent';
          const command = editor.commands.get(commandName);
          if (command && command.isEnabled) {
            editor.execute(commandName);
          } else if (import.meta.env?.DEV) {
            console.warn(`[TabToSpacesPlugin] ${commandName} command not enabled for list block`);
          }
        } else {
          // Plain text blocks (paragraphs/headings): visual indent via the
          // customIndent attribute (margin-inline-start), RTL/LTR aware.
          editor.model.change((writer: any) => {
            for (const block of group) {
              const currentIndent = parseInt(block.getAttribute('customIndent') || '0', 10);
              if (isShift) {
                if (currentIndent > 0) {
                  writer.setAttribute('customIndent', currentIndent - 1, block);
                }
              } else {
                writer.setAttribute('customIndent', currentIndent + 1, block);
              }
            }
          });
        }
      }

      // Restore selection across all originally selected blocks
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
      // Hack to get the ButtonView class from an existing button
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

const editorConfig = ref({
  toolbar: [
    'formatPainter',
    '|',
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
  ],
  language: 'ar',
  pasteFromWordRemoveFontStyles: false,
  extraPlugins: [CustomIndentPlugin, TabToSpacesPlugin, DirectionPlugin, FormatPainterPlugin],
  mediaEmbed: {
    previewsInData: true,
    extraProviders: [
      {
        name: 'all_iframes',
        url: [
            /^http:\/\/196\.1\.226\.242\/(.+)/,
            /^https?:\/\/(.+)/
        ],
        html: match => {
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
});

// 1. Sync Parent -> Child
// When axios updates the data in the parent, this updates the editor content
watch(
  () => props.modelValue,
  (newVal) => {
    if (newVal !== editorData.value) {
      editorData.value = newVal || '';
    }
  }
);

// 2. Sync Child -> Parent
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
.ck-editor__editable {
  min-height: 100px;
  font-weight: normal;
}

.ck-content ul,
.ck-content ol {
  padding: 0 1em;
}

.ck-content [dir='ltr'] {
  text-align: left !important;
}

.ck-content [dir='rtl'] {
  text-align: right !important;
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