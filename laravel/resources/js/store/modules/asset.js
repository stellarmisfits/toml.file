import axios from 'axios'
import { fetchLinkedOrgs, fetchUnlinkedOrgs } from '~/store/linked-orgs'

export const state = {
  assets: [],
  linkedOrgs: [],
  unlinkedOrgs: []
}

// getters
export const getters = {
  getAssetByUuid: (state) => (uuid) => {
    return state.assets.find(asset => asset.uuid === uuid)
  },
  getAssetBySlug: (state) => (slug) => {
    return state.assets.find(asset => asset.slug === slug)
  },
  assets: state => (state.assets.length) ? state.assets : null,
  linkedOrgs: state => state.linkedOrgs,
  unlinkedOrgs: state => state.unlinkedOrgs
}

// actions
export const actions = {
  async fetchAssets ({ commit }) {
    const { data } = await axios.get('/api/assets')
    commit('SET_ASSETS', { assets: data.data })
  },

  async fetchAsset ({ commit, getters }, options) {
    let asset = getters.getAssetByUuid(options.uuid)

    if (!asset || options.force) {
      const { data } = await axios.get('/api/assets/' + options.uuid)
      commit('SET_ASSET', { asset: data.data })
    }
  },

  fetchLinkedOrgs,
  fetchUnlinkedOrgs
}

// mutations
export const mutations = {
  SET_ASSETS (state, { assets }) {
    state.assets = assets
  },
  SET_ASSET (state, { asset }) {
    state.assets = [
      ...state.assets.filter(element => element.uuid !== asset.uuid),
      asset
    ]
  },
  SET_LINKED_ORGS (state, { orgs }) {
    state.linkedOrgs = orgs
  },
  SET_UNLINKED_ORGS (state, { orgs }) {
    state.unlinkedOrgs = orgs
  }
}
