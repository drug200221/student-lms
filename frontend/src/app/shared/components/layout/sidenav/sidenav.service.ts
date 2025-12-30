import { Injectable, signal } from '@angular/core';
import { debounceTime, fromEvent } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class SidenavService {
  public isLargeScreen = signal(window.innerWidth >= 768);
  public isOpened =  signal(this.isLargeScreen());

  constructor() {
    fromEvent(window, 'resize')
      .pipe(
        debounceTime(100)
      )
      .subscribe(() => {
        const width = window.innerWidth;
        const isLarge = width >= 768;

        this.isLargeScreen.set(isLarge);
        this.isOpened.set(isLarge);
      });
  }

  public toggle() {
    this.isOpened.update(v => !v);
  }
}
