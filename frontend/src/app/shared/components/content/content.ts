import { Component, computed, inject } from '@angular/core';
import { MatCard, MatCardContent, MatCardHeader, MatCardTitle } from '@angular/material/card';
import { MatDivider } from '@angular/material/list';
import { MatProgressSpinner } from '@angular/material/progress-spinner';
import { MarkdownComponent } from 'ngx-markdown';
import { ContentState } from '../../states/content-state';

@Component({
  imports: [
    MarkdownComponent,
    MatCard,
    MatDivider,
    MatProgressSpinner,
    MatCardContent,
    MatCardTitle,
    MatCardHeader,
  ],
  selector: 'psk-content',
  standalone: true,
  styles: `
    mat-card-content markdown {
      display: block;
      width: 100%;
      overflow-wrap: break-word;
      word-wrap: break-word;
      word-break: break-word;
      white-space: normal;
    }

    mat-card-content markdown pre {
      word-break: break-all;
      max-width: 100%;
      overflow-x: auto;
    }

    mat-card {
      min-width: 0;
      max-width: 100%;
      box-sizing: border-box;
    }
  `,
  templateUrl: './content.html',
})
export class Content {
  public contentState = inject(ContentState);

  public readonly data = computed(() => {
    const res = this.contentState.data.value();
    return res?.result ?? null;
  });

  public readonly safeTitle = computed(() => {
    return  this.data()?.title;
  });

  public readonly safeContent = computed(() => {
    return this.data()?.content;
  });
}
