import axios from 'axios'

export const state = {
  notFound: false,
  account: {
    id: null,
    account_id: null,
    sequence: null,
    subentry_count: null,
    home_domain: null,
    last_modified_ledger: null,
    thresholds: {
      low_threshold: null,
      med_threshold: null,
      high_threshold: null
    },
    flags: {
      auth_required: null,
      auth_revocable: null,
      auth_immutable: null
    },
    balances: [
      // {
      //   balance: null,
      //   buying_liabilities: null,
      //   selling_liabilities: null,
      //   asset_type: null
      // }
    ],
    signers: [
      // {
      //   weight: null,
      //   key: null,
      //   type: null
      // }
    ]
  }
}

// getters
export const getters = {
  getAccount: (state) => state.account,
  getAccountNotFound: (state) => state.notFound
}

// actions
export const actions = {
  async fetchAccount ({ commit, getters }, publicKey) {
    try {
      const { data } = await axios({
        method: 'get',
        url: 'accounts/' + publicKey,
        baseURL: window.config.horizonHost
      })
      commit('SET_ACCOUNT', { account: data })
    } catch (e) {
      if (e.response.status === 404) {
        commit('FETCH_ACCOUNT_NOT_FOUND', { notFound: true })
      }
    }
  }
}

// mutations
export const mutations = {
  FETCH_ACCOUNT_NOT_FOUND (state, { notFound }) {
    state.notFound = notFound
  },

  SET_ACCOUNT (state, { account }) {
    state.account = account
  }
}
