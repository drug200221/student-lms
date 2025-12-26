import { Component, inject } from '@angular/core';
import { PrimeIcons } from "primeng/api";
import { Button } from 'primeng/button';
import { Drawer } from "primeng/drawer";
import { MenuComponent } from "../menu/menu.component";
import { SidebarService } from "./sidebar.service";

@Component({
  imports: [
    Drawer,
    MenuComponent,
    Button,
  ],
  selector: 'psk-sidebar',
  standalone: true,
  styleUrl: './sidebar.component.scss',
  templateUrl: './sidebar.component.html',
})
export class SidebarComponent {
  public sidebarService = inject(SidebarService);
  protected readonly PrimeIcons = PrimeIcons;
}
