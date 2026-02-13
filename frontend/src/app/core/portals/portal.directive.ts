import { Directive, inject, OnInit, OnDestroy } from '@angular/core';
import { CdkPortal } from '@angular/cdk/portal';
import { PortalService } from './portal.service';

@Directive({
  selector: '[pskPortal]',
  standalone: true,
})
export class PortalDirective implements OnInit, OnDestroy {
  private portal = inject(CdkPortal);
  private service = inject(PortalService);

  public ngOnInit(): void {
    this.service.set(this.portal);
  }

  public ngOnDestroy(): void {
    this.service.clear();
  }
}
