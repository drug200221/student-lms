import { NodeSpec, Schema } from 'prosemirror-model';
import { nodes as basicNodes, marks } from 'ngx-editor';
import { tableNodes } from 'prosemirror-tables';
import OrderedMap from 'orderedmap';

const tNodes = tableNodes({
  tableGroup: 'block',
  cellContent: 'block+',
  cellAttributes: {
    background: {
      default: null,
      getFromDOM(dom: any) {
        return dom.style.backgroundColor || null;
      },
      setDOMAttr(value, attrs) {
        if (value) {
          attrs['style'] = (attrs['style'] || '') + `background-color: ${value};`;
        }
      },
    },
  },
});

const nodesMap = OrderedMap.from(basicNodes).append(tNodes);

export const customNgxSchema = new Schema({
  nodes: nodesMap as unknown as OrderedMap<NodeSpec>,
  marks,
});
