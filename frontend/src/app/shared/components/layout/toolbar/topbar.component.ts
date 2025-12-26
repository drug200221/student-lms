import { Component, inject } from '@angular/core';
import { PrimeIcons } from 'primeng/api';
import { Button } from 'primeng/button';
import { FloatLabel } from 'primeng/floatlabel';
import { Toolbar } from 'primeng/toolbar';
import { SidebarService } from '../sidebar/sidebar.service';

@Component({
  imports: [
    Toolbar,
    FloatLabel,
    Button,
  ],
  selector: 'psk-toolbar',
  standalone: true,
  templateUrl: './topbar.component.html',
})
export class TopbarComponent {
  public sidebarService = inject(SidebarService);
  protected readonly PrimeIcons = PrimeIcons;
}
