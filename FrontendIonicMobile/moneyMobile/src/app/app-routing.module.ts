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
  { path: 'connexion', component: ConnexionComponent},
  { path: 'accueil', component: AccueilComponent },
  { path: 'depot', component: DepotFormulaireComponent },
  { path : 'fraisCalculator', component : FraisCalculatorComponent}
];
@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules }),
    HttpClientModule

  ],
  exports: [RouterModule]
})
export class AppRoutingModule {}
