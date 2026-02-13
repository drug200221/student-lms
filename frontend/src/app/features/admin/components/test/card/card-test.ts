import { Component } from '@angular/core';
import { MatCardActions, MatCardModule } from '@angular/material/card';
import { MatIcon } from '@angular/material/icon';
import { MatMenu, MatMenuItem, MatMenuTrigger } from '@angular/material/menu';
import { MatIconButton } from '@angular/material/button';
import { MatDivider } from '@angular/material/list';

@Component({
  imports: [
    MatCardModule,
    MatIcon,
    MatCardActions,
    MatMenu,
    MatMenuItem,
    MatMenuTrigger,
    MatIconButton,
    MatDivider,
  ],
  selector: 'psk-card-test',
  standalone: true,
  styles: `
    mat-card {
      display: flex;
      width: 100%;
      height: 100%;
      gap: .8rem;

      mat-card-content {
        line-height: 1rem;
      }

      mat-card-actions {
        margin: 0 auto;
      }
    }
  `,
  templateUrl: './card-test.html',
})
export class CardTest {
  protected test = {
    name: 'Название теста название теста название теста название теста',
    startDate: '2024-02-02',
    endDate: '2024-02-02',
    attempts: '1/1',
    questionCount: 10,
    duration: '60 минут',
  };
}
