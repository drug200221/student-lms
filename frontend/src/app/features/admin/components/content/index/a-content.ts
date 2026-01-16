import { Component, computed, inject } from '@angular/core';
import { MatButton, MatIconButton } from '@angular/material/button';
import { MatCardActions, MatCardModule } from '@angular/material/card';
import { MatIcon } from '@angular/material/icon';
import { RouterLink } from '@angular/router';
import { Content } from '../../../../../shared/components/content/content';
import { ContentState } from '../../../../../shared/states/content-state';

@Component({
  imports: [
    Content,
    MatCardModule,
    MatIcon,
    MatCardActions,
    MatButton,
    RouterLink,
    MatIconButton,
  ],
  selector: 'psk-a-content',
  standalone: true,
  styles: ``,
  templateUrl: './a-content.html',
})
export class AContent {
  public contentState = inject(ContentState);

  public readonly content = computed(() => this.contentState.data.value()?.result);

  public openDialog() { /* empty */ }
}
