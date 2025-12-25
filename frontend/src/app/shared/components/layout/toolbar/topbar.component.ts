import { Component, inject } from '@angular/core';
import { FloatLabel } from 'primeng/floatlabel';
import { Toolbar } from 'primeng/toolbar';
import { SidebarService } from '../sidebar/sidebar.service';

@Component({
  imports: [
    Toolbar,
    FloatLabel,
  ],
  selector: 'psk-toolbar',
  standalone: true,
  styleUrl: './topbar.component.scss',
  templateUrl: './topbar.component.html',
})
export class TopbarComponent {
  public sidebarService = inject(SidebarService);
}
