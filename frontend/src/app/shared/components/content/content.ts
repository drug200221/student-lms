import { Component, computed, inject, ViewEncapsulation } from '@angular/core';
import { MatCard, MatCardContent, MatCardHeader, MatCardTitle } from '@angular/material/card';
import { MatDivider } from '@angular/material/list';
import { MatProgressSpinner } from '@angular/material/progress-spinner';
import { MarkdownComponent } from 'ngx-markdown';
import { ContentState } from '../../states/content-state';

@Component({
  encapsulation: ViewEncapsulation.None,
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
    mat-card {
      mat-card-header {
        width: 100%;
        overflow: hidden;

        .mat-mdc-card-header-text {
          width: 100% !important;
          overflow: hidden !important;

          mat-card-title {
            line-height: 1.4;

            markdown {
              h2 {
                width: 100%;
                white-space: wrap;
                margin: 0;
              }
            }
          }
        }
      }

      mat-card-title markdown,
      mat-card-content markdown {
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
