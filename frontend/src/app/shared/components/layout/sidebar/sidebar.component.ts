import { Component, HostListener, inject, OnInit } from '@angular/core';
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
export class SidebarComponent implements OnInit {
  public sidebarService = inject(SidebarService);
  protected readonly PrimeIcons = PrimeIcons;

  @HostListener('window:resize', ['$event'])
  public onResize(event: Event) {
    const target = event.target as Window;
    const width = target.innerWidth;
    const isLarge = width > 920;

    if (!this.sidebarService.isToggled()) {
      this.sidebarService.isOpened.set(isLarge);
    }
  }

  public ngOnInit() {
    if (this.sidebarService.isOpened() === null) {
      this.sidebarService.isOpened.set(window.innerWidth > 920);
    }
  }
}
