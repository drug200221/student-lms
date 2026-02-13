import { Injectable, signal } from '@angular/core';
import { Portal } from '@angular/cdk/portal';

@Injectable({ providedIn: 'root' })
export class PortalService {
  private readonly _portal = signal<Portal<unknown> | null>(null);

  public readonly activePortal = this._portal.asReadonly();

  public set(portal: Portal<unknown> | null): void {
    this._portal.set(portal);
  }

  public clear(): void {
    this._portal.set(null);
  }
}
