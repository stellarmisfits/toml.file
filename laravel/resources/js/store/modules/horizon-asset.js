import axios from 'axios'

export const state = {
  notFound: false,
  asset: {
    _links: {
      toml: {
        href: null
      }
    },
    asset_type: null,
    asset_code: null,
    asset_issuer: null,
    paging_token: null,
    amount: null,
    num_accounts: null,
    flags: {
      auth_required: null,
      auth_revocable: null,
      auth_immutable: null
    }
  }
}

// getters
export const getters = {
  getAsset: (state) => state.asset,
  getAssetNotFound: (state) => state.notFound
}

// actions
export const actions = {
  async fetchAsset ({ commit, getters }, asset) {
    try {
      const { data } = await axios({
        method: 'get',
        url: 'assets',
        params: { asset_issuer: asset.account_public_key, asset_code: asset.code },
        baseURL: window.config.horizonHost
      })

      if (data._embedded.records.length === 1) {
        commit('SET_ASSET', { asset: data._embedded.records[0] })
        return
      }

      commit('FETCH_ASSET_NOT_FOUND', { notFound: true })
    } catch (e) {
      throw e
    }
  }
}

// mutations
export const mutations = {
  FETCH_ASSET_NOT_FOUND (state, { notFound }) {
    state.notFound = notFound
  },

  SET_ASSET (state, { asset }) {
    state.asset = asset
  }
}
