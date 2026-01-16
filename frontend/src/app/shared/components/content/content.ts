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
  templateUrl: './content.html',
})
export class Content {
  public contentState = inject(ContentState);

  public readonly content = computed(() => {
    const response = this.contentState.data.value();
    const currentId = this.contentState.id();

    if (!response?.result || !currentId) {
      return null;
    }

    return response.result ?? null;
  });
}
