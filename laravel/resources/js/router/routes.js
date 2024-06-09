function page (path) {
  return () => import(/* webpackChunkName: '' */ `~/pages/${path}`).then(m => m.default || m)
}

export default [
  { path: '/', name: 'welcome', component: page('welcome.vue') },

  { path: '/login', name: 'login', component: page('auth/login.vue') },
  { path: '/register', name: 'register', component: page('auth/register.vue') },
  { path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
  { path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue') },
  { path: '/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue') },
  { path: '/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue') },

  { path: '/privacy', name: 'privacy', component: page('auth/privacy.vue') },
  { path: '/terms', name: 'terms', component: page('auth/terms.vue') },
  { path: '/agreement', name: 'agreement', component: page('auth/agreement.vue') },

  { path: '/app', name: 'dashboard', component: page('home.vue') },
  { path: '/settings',
    component: page('settings/index.vue'),
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue') },
      { path: 'password', name: 'settings.password', component: page('settings/password.vue') }
    ]
  },

  /**
   * Organizations
   */
  { path: '/app/orgs/:uuid',
    name: 'org',
    component: page('orgs/show.vue'),
    children: [
      { path: '', redirect: { name: 'org.details' } },
      { path: 'details', name: 'org.details', component: page('orgs/details.vue') },
      { path: 'resources', name: 'org.resources', component: page('orgs/resources.vue') },
      { path: 'documentation', name: 'org.documentation', component: page('orgs/documentation.vue') },
      { path: 'globals', name: 'org.globals', component: page('orgs/globals.vue') },
      { path: 'toml', name: 'org.toml', component: page('orgs/toml.vue') }
    ]
  },

  /**
   * Accounts
   */
  { path: '/app/accounts', name: 'accounts', component: page('accounts/index.vue') },
  { path: '/app/accounts/:uuid',
    name: 'account',
    component: page('accounts/show.vue'),
    children: [
      { path: '', redirect: { name: 'account.details' } },
      { path: 'details', name: 'account.details', component: page('accounts/details.vue') },
      { path: 'network', name: 'account.network', component: page('accounts/network.vue') }
    ]
  },

  /**
   * Assets
   */
  { path: '/app/assets', name: 'assets', component: page('assets/index.vue') },
  { path: '/app/assets/:uuid',
    name: 'asset',
    component: page('assets/show.vue'),
    children: [
      { path: '', redirect: { name: 'asset.details' } },
      { path: 'details', name: 'asset.details', component: page('assets/details.vue') },
      { path: 'anchor', name: 'asset.anchor', component: page('assets/anchor.vue') },
      { path: 'regulated', name: 'asset.regulated', component: page('assets/regulated.vue') },
      { path: 'network', name: 'asset.network', component: page('assets/network.vue') }
    ]
  },

  /**
   * Principals
   */
  { path: '/app/principals', name: 'principals', component: page('principals/index.vue') },
  { path: '/app/principals/:uuid',
    name: 'principal',
    component: page('principals/show.vue'),
    children: [
      { path: '', redirect: { name: 'principal.details' } },
      { path: 'details', name: 'principal.details', component: page('principals/details.vue') }
    ]
  },

  /**
   * Validators
   */
  { path: '/app/validator', name: 'validators', component: page('validators/index.vue') },
  { path: '/app/validators/:uuid',
    name: 'validator',
    component: page('validators/show.vue'),
    children: [
      { path: '', redirect: { name: 'validator.details' } },
      { path: 'details', name: 'validator.details', component: page('validators/details.vue') }
    ]
  },

  { path: '*', name: '404', component: page('errors/404.vue') }
]
