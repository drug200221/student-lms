import { inject, Injectable } from '@angular/core';
import { MarkdownService as NgxMarkdownService } from 'ngx-markdown'; // Встроенный сервис
import TurndownService from 'turndown';
import { gfm } from '@truto/turndown-plugin-gfm';

@Injectable({
  providedIn: 'root',
})
export class MarkdownConverterService {
  private td: TurndownService;
  private ngxMarkdown = inject(NgxMarkdownService);

  constructor() {
    this.td = new TurndownService({
      headingStyle: 'atx',
      hr: '---',
      bulletListMarker: '*',
      codeBlockStyle: 'fenced',
    });

    this.td.use(gfm);

    this.td.addRule('inlineLink', {
      filter: 'a',
      replacement: (content, node) => {
        const el = node as HTMLElement;
        const href = el.getAttribute('href');

        return href ? `[${content}](${href})` : content;
      },
    });
  }

  public toMarkdown(html: string): string {
    return html ? this.td.turndown(html) : '';
  }

  public async toHtml(markdown: string) {
    return this.ngxMarkdown.parse(markdown);
  }
}
