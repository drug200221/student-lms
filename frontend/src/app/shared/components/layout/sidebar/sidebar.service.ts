import { Injectable, signal } from '@angular/core';
import { takeUntilDestroyed } from '@angular/core/rxjs-interop';
import { debounceTime, fromEvent } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class SidebarService {
  public isLargeScreen = signal(window.innerWidth >= 920);
  public isOpened =  signal(this.isLargeScreen());

  constructor() {
    fromEvent(window, 'resize')
      .pipe(
        debounceTime(100),
        takeUntilDestroyed()
      )
      .subscribe(() => {
        const width = window.innerWidth;
        const isLarge = width >= 920;

        this.isLargeScreen.set(isLarge);
        this.isOpened.set(isLarge);
      });
  }

  public toggle() {
    this.isOpened.update(v => !v);
  }
}
