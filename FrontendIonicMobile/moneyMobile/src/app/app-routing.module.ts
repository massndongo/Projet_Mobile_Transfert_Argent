import { AuthGuardService } from './services/guard/authguard.service';
import { RetraitFormulaireComponent } from './retrait-formulaire/retrait-formulaire.component';
import { FraisCalculatorComponent } from './frais-calculator/frais-calculator.component';
import { AccueilComponent } from './accueil/accueil.component';
import { HttpClientModule } from '@angular/common/http';
import { ConnexionComponent } from './connexion/connexion.component';
import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';
import { DepotFormulaireComponent } from './depot-formulaire/depot-formulaire.component';

const routes: Routes = [
  { path: 'home', loadChildren: () => import('./home/home.module').then( m => m.HomePageModule) },
  { path: '', redirectTo: 'home', pathMatch: 'full' },
  { path: 'connexion', component: ConnexionComponent, canActivate: [AuthGuardService] },
  { path: 'accueil', component: AccueilComponent, canActivate: [AuthGuardService] },
  { path: 'depot', component: DepotFormulaireComponent, canActivate: [AuthGuardService]  },
  { path : 'fraisCalculator', component : FraisCalculatorComponent, canActivate: [AuthGuardService] },
  { path : 'retrait', component : RetraitFormulaireComponent, canActivate: [AuthGuardService] }
];
@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules }),
    HttpClientModule

  ],
  exports: [RouterModule]
})
export class AppRoutingModule {}
