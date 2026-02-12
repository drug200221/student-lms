import { schema as baseSchema } from 'ngx-editor';
import { Schema, NodeSpec, MarkSpec } from 'prosemirror-model';
import { tableNodes } from 'prosemirror-tables';

const markdownMarks: Record<string, MarkSpec> = {};
const baseMarks = baseSchema.spec.marks.toObject();
const forbidden = ['text_color', 'text_background_color', 'underline'];

for (const key in baseMarks) {
  if (!forbidden.includes(key)) {
    markdownMarks[key] = baseMarks[key];
  }
}

const cleanNodes: Record<string, NodeSpec> = {};
const baseNodes = baseSchema.spec.nodes.toObject();

for (const nodeName in baseNodes) {
  const node = baseNodes[nodeName];
  let attrs = node.attrs || {};
  let parseDOM = node.parseDOM;
  let toDOM = node.toDOM;
  let selectable = node.selectable ?? true;
  let draggable = node.draggable ?? true;

  if (nodeName === 'heading') {
    attrs = { level: { default: 1 } };
  } else if (nodeName === 'image') {
    selectable = false;
    draggable = false;
    attrs = {
      src: { default: null },
      alt: { default: null },
      title: { default: null },
    };

    parseDOM = [{
      tag: 'img[src]',
      getAttrs: (dom: HTMLElement) => ({
        src: dom.getAttribute('src'),
        title: dom.getAttribute('title'),
        alt: dom.getAttribute('alt'),
      }),
    }];

    toDOM = (node) => ['img', node.attrs];
  }

  cleanNodes[nodeName] = {
    ...node,
    attrs,
    parseDOM,
    toDOM,
    selectable,
    draggable,
  };
}

const markdownTableNodes = tableNodes({
  tableGroup: 'block',
  cellContent: 'block+',
  cellAttributes: {},
});

const finalNodes = {
  ...cleanNodes,
  ...markdownTableNodes,
};

const customNgxSchema = new Schema({
  nodes: finalNodes,
  marks: markdownMarks,
});

export default customNgxSchema;
