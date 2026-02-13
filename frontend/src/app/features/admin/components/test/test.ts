import { Component } from '@angular/core';
import { MatCardModule } from '@angular/material/card';
import { CardTest } from './card/card-test';
import { MatFabButton } from '@angular/material/button';
import { RouterLink } from '@angular/router';
import { MatIcon } from '@angular/material/icon';

@Component({
  imports: [
    MatCardModule,
    CardTest,
    RouterLink,
    MatFabButton,
    MatIcon,
  ],
  selector: 'psk-test',
  standalone: true,
  styles: `
    .control {
      display: flex;
      width: 100%;
      gap: 1rem;
    }
    .content {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(28rem, 1fr));
      width: 100%;
      gap: 1rem;
    }
  `,
  templateUrl: './test.html',
})
export class Test {

}
