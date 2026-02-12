import { Toolbar } from 'ngx-editor';

export const miniToolbar: Toolbar = [
  ['bold', 'italic', 'strike'],
  ['code', 'blockquote'],
  ['link'],
];

export const  toolbar: Toolbar = [
  ['bold', 'italic', 'strike'],
  ['code', 'blockquote'],
  ['ordered_list', 'bullet_list'],
  [{ heading: ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] }],
  ['link', 'image'],
  ['horizontal_rule'],
];
