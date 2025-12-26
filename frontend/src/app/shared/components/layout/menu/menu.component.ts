import { Component, OnInit } from '@angular/core';
import { RouterModule } from '@angular/router';
import { MenuItem, PrimeIcons } from 'primeng/api';

@Component({
  imports: [
    RouterModule,
  ],
  selector: 'psk-menu',
  standalone: true,
  template: `
    @for (item of items; track item.label) {
      <a
        [routerLink]="item.routerLink"
        [routerLinkActiveOptions]="{ exact: true }"
        routerLinkActive="text-secondary"
        class="flex flex-column align-items-center text-center p-3"
      >
        <i [class]="item.icon" class="text-2xl mb-2"></i>
        <span class="text-xs uppercase" style="letter-spacing: 0.05rem">
          {{ item.label }}
        </span>
      </a>
    }
  `,
})
export class MenuComponent implements OnInit {
  public items: MenuItem[] = [];

  public ngOnInit() {
    this.items = [
      {
        label: 'Содержание',
        icon: PrimeIcons.BOOK,
        routerLink: ['/'],
      },
      {
        label: 'Тесты',
        icon: PrimeIcons.CHECK_CIRCLE,
        routerLink: ['/tests'],
      },
      {
        label: 'Хранилище файлов',
        icon: PrimeIcons.FOLDER,
        routerLink: ['/tests'],
      },
    ];
  }
}
