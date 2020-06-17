import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import {AppRouting} from "./app.routing";

import {AppComponent} from './app.component';
import {LoginComponent} from './components/user/login/login.component';
import {RegisterComponent} from './components/user/register/register.component';
import {HomeComponent} from './components/structure/home/home.component';
import {PageNotFoundComponent} from './components/structure/page-not-found/page-not-found.component';
import {FormsModule, ReactiveFormsModule} from "@angular/forms";

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    RegisterComponent,
    HomeComponent,
    PageNotFoundComponent
  ],
  imports: [
    BrowserModule,
    AppRouting,
    ReactiveFormsModule,
    FormsModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule {
}
